<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Delete old reset tokens
        EmailVerification::where('user_id', $user->id)->delete();

        // Create new reset token
        $token = Str::random(60);
        EmailVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(60),
        ]);

        $user->notify(new \App\Notifications\PasswordResetNotification($token));

        return back()->with('success', 'Password reset link sent! Please check your inbox.');
    }

    public function showResetForm($token)
    {
        $verification = EmailVerification::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired reset link.');
        }

        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $verification = EmailVerification::where('token', $request->token)
            ->where('email', $request->email)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return back()->with('error', 'Invalid or expired reset link.');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        $verification->delete();

        return redirect()->route('login')
            ->with('success', 'Password reset successfully!');
    }
}
