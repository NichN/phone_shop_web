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
                <div>
                    <button class="btn btn-outline-warning btn-sm editsize" data-id="' . $row->id . '" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm deletesize" data-id="' . $row->id . '" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>';

            })
            ->rawColumns(['action']) 
            ->make(true);
    }
        return view('Admin.payment.index');
    }
}
