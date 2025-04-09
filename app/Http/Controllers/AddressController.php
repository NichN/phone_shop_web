<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|size:2',
        ]);

        try {
            Auth::user()->update($validated);
            return back()->with('success', 'Address saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving address: '.$e->getMessage());
        }
    }
}