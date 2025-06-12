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
            $customer = DB::table('users')->get();
            return DataTables::of($customer)
            ->addColumn('action', function ($row) {
                $btn = '<div>
                            <button class="btn btn-primary btn-sm editCate" data-id="' . $row->id . '" data-toggle="tooltip" title="Edit">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm deleteCate" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete">
                                Delete
                            </button>
                        </div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }
        return view('Admin.customer.index');
    }
}
