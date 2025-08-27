<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profiles', 'public');

        // Optional: delete old image if exists
        if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
            \Storage::disk('public')->delete($user->profile_image);
        }

        $user->profile_image = $imagePath;
    }

    // Assign new values
    $user->name = $request->name;
    $user->phone_number = $request->phone_number;
    $user->email = $request->email;

    if ($user->save()) {
        return back()->with('success', 'Profile updated successfully.');
    } else {
        return back()->with('error', 'Failed to update profile.');
    }
}





    public function changePassword(Request $request)
{
    try {
        // Validate input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Prevent admin users from changing their own password
        if ($user->role_id == 1) { // Assuming role_id 1 is admin
            return response()->json([
                'success' => false,
                'message' => 'Admin users cannot change their own password for security reasons'
            ], 403);
        }

        // Debug log
        \Log::info('Password change attempt for user: ' . $user->email);
        \Log::info('Current password provided: ' . $request->current_password);
        \Log::info('New password provided: ' . $request->new_password);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            \Log::warning('Current password verification failed for user: ' . $user->email);
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        \Log::info('Current password verified successfully');

        // Update password
        try {
            DB::beginTransaction();
            
            $user->password = Hash::make($request->new_password);
            $saved = $user->save();

            if (!$saved) {
                throw new \Exception('Failed to save new password');
            }

            DB::commit();
            \Log::info('Password updated successfully for user: ' . $user->email);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update password: ' . $e->getMessage());
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed: ' . json_encode($e->errors()));
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Password change error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating your password'
        ], 500);
    }
}


    public function updateAddress(Request $request)
{
    $request->validate([
        'address_line1' => 'required|string',
        'address_line2' => 'nullable|string',
        'city' => 'required|string',
        'state' => 'required|string',
    ]);

    $user = Auth::user();
    $user->address_line1 = $request->address_line1;
    $user->address_line2 = $request->address_line2;
    $user->city = $request->city;
    $user->state = $request->state;
    $user->save();

    return back()->with('success', 'Address updated successfully.');
}
}

