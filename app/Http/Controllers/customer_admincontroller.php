<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class customer_admincontroller extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $customer = DB::table('users')
                ->where('role_id', '>', 3) // Exclude customers (role_id = 4)
                ->get();
            return DataTables::of($customer)
            ->addColumn('action', function ($row) {
                $btn = '<div>
                            <div>
                            <button class="btn btn-sm editCate" data-id="'. $row->id .'" data-toggle="tooltip" title="Edit" style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="'.$row->id.'" class="btn btn-sm deleteCate" style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        </div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }
        return view('Admin.customer.index');
    }
}
