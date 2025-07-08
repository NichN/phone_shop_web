<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Order_dashboard_controller extends Controller
{
    public function index()
    {
        $totalOrder = DB::table('orders')
            ->count();

        $totalCanceled = DB::table('orders')
            ->where('status', 'cancelled')
            ->count();

        // Total Processing Orders
        $totalProcessing = DB::table('orders')
            ->where('status', 'processing')
            ->count();

        // Total Completed Orders
        $totalCompleted = DB::table('orders')
            ->where('status', 'completed')
            ->count();

        $totalIncome = DB::table('orders')
            ->where('status', 'completed')
            ->sum('total_amount');

        return view('Admin.order.index', [
            'total_order'     => $totalOrder,
            'total_canceled'  => $totalCanceled,
            'total_processing'=> $totalProcessing,
            'total_completed' => $totalCompleted,
            'total_income'    => $totalIncome,
        ]);
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('orders')->get();
            // dd($data);
            return DataTables::of($data)
        ->addColumn('action', function ($row) {
            $id = $row->id;
            $dropdown = '
                <div class="dropdown">
                    <a class="text-dark" href="#" role="button" id="dropdownMenu' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu' . $id . '">
                        <li>
                            <a class="dropdown-item vieworder" href="' . route('delivery_option.show', ['id' => $id]) . '" data-id="' . $id . '">
                                <i class="fa fa-eye text-primary"></i> Order Detail
                            </a>
                        </li>
                        <li>
                            <form action="' . route('checkout.accept', $id) . '" method="POST" onsubmit="return confirm(\'Accept this order?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="dropdown-item text-success">
                                    <i class="fas fa-check-circle"></i> Accept Order
                                </button>
                            </form>
                        </li>
                        <li>
                            <li>
                            <form action=" ' . route('checkout.decline', $id) .'" method="POST" onsubmit="return confirm(\'Decline this order? \');">
                                ' . csrf_field() . '
                                <button type="submit" class="dropdown-item text-danger decline-button">
                                    <i class="fas fa-times-circle"></i> Decline Order
                                </button>
                            </form>
                        </li>
                        </li>
                    </ul>
                </div>';

            return $dropdown;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
        return response()->json(['error' => 'Unauthorized request.'], 403);
    }

}
