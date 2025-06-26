<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('Admin.role.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('id', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<button class="btn btn-warning btn-sm editRoleBtn" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button class="btn btn-danger btn-sm deleteRoleBtn" data-url="/admin/roles/'.$row->id.'"><i class="fas fa-trash-alt"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $role = Role::create(['name' => $request->name]);
        return response()->json(['success' => 'Role created successfully.']);
    }

    public function edit(Role $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
        ]);
        $role->update(['name' => $request->name]);
        return response()->json(['success' => 'Role updated successfully.']);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['success' => 'Role deleted successfully.']);
    }
} 