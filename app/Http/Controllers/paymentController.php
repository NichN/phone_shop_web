<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Order;

class paymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payment = DB::table('payment')
        ->join('orders', 'orders.id', '=', 'payment.order_id')
        ->whereNotIn('orders.status', ['cancelled', 'pending'])
        ->get();
            return DataTables::of($payment)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-outline-primary btn-sm view-invoice" data-id="' . $row->order_id . '" title="View Invoice">
                            <i class="fas fa-file-invoice"></i> View
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Admin.payment.index');
    }

   public function order_detail($id)
{
    $order = Order::findOrFail($id);

    $orderItems = DB::table('order_item')
        ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
        ->where('order_item.order_id', $order->id)
        ->select([
            'order_item.quantity',
            'order_item.price',
            'product_item.product_name',
            'product_item.images as imgSrc',
            'product_item.color_code',
            'product_item.size',
            'product_item.warranty'
        ])
        ->get();

    $payment = DB::table('payment')
        ->where('order_id', $order->id)
        ->select('payment_type')
        ->first();

    // Return only the invoice partial view
    return view('Admin.payment.invoice_partial', compact('order', 'orderItems', 'payment'));
}



}
