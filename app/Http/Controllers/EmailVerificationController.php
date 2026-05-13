<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    public function verify($token)
    {
        $verification = EmailVerification::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('verified_at')
            ->first();

        if (!$verification) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired verification link.');
        }

        $user = $verification->user;
        $user->email_verified = true;
        $user->email_verified_at = Carbon::now();
        $user->save();

        $verification->verified_at = Carbon::now();
        $verification->save();

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now login.');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->email_verified) {
            return back()->with('info', 'Email already verified.');
        }

        // Delete old verification tokens
        EmailVerification::where('user_id', $user->id)->delete();

        // Create new verification token
        $token = Str::random(60);
        EmailVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(60),
        ]);

        $user->notify(new \App\Notifications\EmailVerificationNotification($token));

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}
