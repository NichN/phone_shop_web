<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ], [
            'new_password.regex' => 'Password must contain at least: 1 uppercase, 1 lowercase, 1 number, and 1 special character'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->with('error', 'New password must be different from current password');
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Password updated successfully!');
        
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating password: ' . $e->getMessage());
        }
    }
}