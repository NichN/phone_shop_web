<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class pick_upController extends Controller
{
    public function index(Request $request)
{
    $totalOrder = DB::table('orders')
        ->where('delivery_type', 'pick up')
        ->count();

    $totalCanceled = DB::table('orders')
        ->where('status', 'cancelled')
        ->where('delivery_type', 'pick up')
        ->count();

    $totalProcessing = DB::table('orders')
        ->where('status', 'processing')
        ->where('delivery_type', 'pick up')
        ->count();

    $totalCompleted = DB::table('orders')
        ->where('status', 'completed')
        ->where('delivery_type', 'pick up')
        ->count();

    $total_feeCompleted = DB::table('orders')
        ->where('status', 'completed')
        ->where('delivery_type', 'pick up')
        ->sum('total_amount');

    $total_feePending = DB::table('orders')
        ->where('status', 'processing')
        ->where('delivery_type', 'pick up')
        ->sum('total_amount');

    if ($request->ajax()) {
        $data = DB::table('orders')
            ->where('delivery_type', 'pick up')
            ->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $dropdown = '
                    <div class="dropdown">
                        <a class="text-dark" href="#" role="button" id="dropdownMenu' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu' . $row->id . '">
                            <li>
                                <a class="dropdown-item vieworder" href="' . route('delivery_option.show', ['id' => $row->id]) . '" data-id="' . $row->id . '">Order Detail</a>
                            </li>';
                             if ($row->status === 'processing') {
                                $dropdown .= '
                                        <li>
                                            <a class="dropdown-item finish text-success" data-id="' . $row->id . '">
                                                <i class="fa-solid fa-circle-check"></i> Finish Order
                                            </a>
                                        </li>';
                            }
                                        $dropdown .= '
                                    </ul>
                                </div>';
                return $dropdown;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('Admin.pick_up.index', [
        'total_order'       => $totalOrder,
        'total_canceled'    => $totalCanceled,
        'total_processing'  => $totalProcessing,
        'total_completed'   => $totalCompleted,
        'total_fee'         => $total_feeCompleted,
        'total_feepending'  => $total_feePending,
    ]);
}

public function finish($id)
{
    $order = DB::table('orders')->where('id', $id)->first();

    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
    }
    DB::table('orders')
        ->where('id', $id)
        ->update(['status' => 'completed']);

    return response()->json(['success' => true, 'message' => 'Order marked as completed.']);
}
}
