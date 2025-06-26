<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordVerificationMail;
use App\Models\User;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('authentication_form.forgetpw');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate reset token
        $token = Str::random(64);
        
        // Debug: Log the token generation
        \Log::info('Generated reset token for email: ' . $request->email . ' - Token: ' . $token);
        
        // Store the token in the password_reset_tokens table
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send email
        try {
            Mail::to($request->email)->send(new PasswordVerificationMail($token, $user->name));
            \Log::info('Reset email sent successfully to: ' . $request->email);
            
            return back()->with('status', 'We have emailed your password reset link!');
        } catch (\Exception $e) {
            \Log::error('Failed to send reset email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset email. Please try again.']);
        }
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid reset link.']);
        }
        
        // Debug: Log the token
        \Log::info('Reset password attempt with token: ' . $token);
        
        // Verify token exists and is not expired
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$resetRecord) {
            \Log::info('Token not found or expired: ' . $token);
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        $email = $resetRecord->email;
        \Log::info('Token valid for email: ' . $email);

        return view('authentication_form.resetpw', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the reset token
        \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }
} 