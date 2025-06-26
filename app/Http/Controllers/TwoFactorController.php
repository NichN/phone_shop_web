<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->save();

        Mail::raw("Your two-factor code is $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Two Factor code');
        });

        return view('authentication_form.two-factor');
    }

    public function verify(Request $request){
        $request->validate([
            'code' => 'required|integer',
        ]);

        $user = auth()->user();

        if ($request->code == $user->two_factor_code){
            // Clear the two-factor code after successful verification
            $user->two_factor_code = null;
            $user->save();
            
            // Set two-factor session with optional remember duration
            if ($request->has('remember_2fa')) {
                // Remember 2FA for 30 days
                session(['two_factor_authenticated' => true]);
                session(['two_factor_expires_at' => now()->addDays(30)->toDateTimeString()]);
                
                // Debug: Log the remember 2FA action
                \Log::info('2FA Remember enabled for user: ' . $user->email . ' until: ' . session('two_factor_expires_at'));
            } else {
                // Standard 2FA session (until browser closes or logout)
                session(['two_factor_authenticated' => true]);
                \Log::info('Standard 2FA session for user: ' . $user->email);
            }
            
            // Role-based redirect after successful two-factor verification
            if ($user->role_id == 1) {
                return redirect()->route('dashboard.show');
            } elseif ($user->role_id == 2) {
                return redirect()->route('dashboard.show');
            } elseif ($user->role_id == 4) {
                return redirect()->route('homepage');
            } else {
                return redirect()->route('homepage');
            }
        }

        return redirect()->route('two_factor.index')->withErrors(['code' => 'The provided code is incorrect.']);
    }
}
