<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $payment= DB::table('payment')
        ->join('orders','orders.id','=','payment.order_id')
        ->get();
        return DataTables::of($payment)
            ->addColumn('action', function ($row) {
                return '
                    <div>
                        <button class="btn btn-primary btn-sm editsize" data-id="' . $row->id . '" title="Edit">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm deletesize" data-id="' . $row->id . '" title="Delete">
                            Delete
                        </button>
                    </div>';
            })
            ->rawColumns(['action']) 
            ->make(true);
    }
        return view('Admin.payment.index');
    }
}
