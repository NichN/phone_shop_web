<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class Admin_user_controller extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('Admin.user.Add_form', compact('roles'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('role')->whereNotNull('role_id')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('group', function($row) {
                    return $row->role ? $row->role->name : 'N/A';
                })
                ->addColumn('action', fn($row) => view('Admin.user.action', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('Admin.user.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'nullable|exists:roles,id',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        $validated = $request->validate($rules);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role ?: null;
        if ($request->hasFile('avatar')) {
            $user->profile_image = $request->file('avatar')->store('profiles', 'public');
        }
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'User created successfully.']);
        }
        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (request()->ajax()) {
            return response()->json($user);
        }
        return view('Admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'nullable|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }
        $validated = $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role ?: null;
        if ($request->hasFile('avatar')) {
            $user->profile_image = $request->file('avatar')->store('profiles', 'public');
        }
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'User updated successfully.']);
        }
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }
}

