<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
public function updateProfile(Request $request)
{
    dd('Controller reached!', $request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();

    // Dump request data to check what is coming
    dd('Request Data:', $request->all());

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

    // Dump user before saving to verify values
    dd('User Before Save:', $user);

    if ($user->save()) {
        // Dump user after save to verify update
        dd('User Saved:', $user);
    } else {
        dd('Failed to save user:', $user);
    }
}





    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Current password is incorrect.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password changed successfully.');
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

