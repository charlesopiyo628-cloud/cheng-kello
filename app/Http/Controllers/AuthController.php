<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            if (!$user->email_verified) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.',
                ]);
            }

            // Check if user needs to change password on first login
            if (isset($user->password_change_required) && $user->password_change_required) {
                Auth::logout();
                return redirect()->route('password.change.first', ['email' => $user->email])
                    ->with('info', 'Please set your password before continuing.');
            }

            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Wrong password or email.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'email_verified' => false,
            'email_verified_at' => null,
            'password_change_required' => false,
        ]);

        $token = Str::random(60);
        EmailVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(60),
        ]);

        $user->notify(new \App\Notifications\EmailVerificationNotification($token));

        return redirect('/')->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function showFirstPasswordChangeForm($email)
    {
        return view('auth.first-password-change', ['email' => $email]);
    }

    public function handleFirstPasswordChange(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->password_change_required = false;
        $user->save();

        return redirect()->route('login')->with('success', 'Password set successfully! You can now login.');
    }

    public function showRegistrationFromLink($token)
    {
        // For now, just redirect to registration form
        // In a real implementation, you might want to validate the token
        // and pre-fill some information
        return redirect()->route('register')->with('info', 'Please complete your registration.');
    }
}
