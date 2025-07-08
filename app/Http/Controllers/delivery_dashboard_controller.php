<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\payment;
use App\Models\deliveries; // Assuming you have a model named 'deliveries'

class delivery_dashboard_controller extends Controller
{
   public function index()
   {
        $totalOrder = DB::table('orders')
            ->where('status', 'paid')
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

        $total_feeCompleted = DB::table('orders')
            ->where('status', 'Completed')
            ->sum('delivery_fee');
        $total_feepending = DB::table('orders')
            ->where('status', 'processing')
            ->sum('delivery_fee');

        return view('Admin.delivery.delivery_processing', [
            'total_order'     => $totalOrder,
            'total_canceled'  => $totalCanceled,
            'total_processing'=> $totalProcessing,
            'total_completed' => $totalCompleted,
            'total_fee'    => $total_feeCompleted,
            'total_feepending' => $total_feepending,
        ]);
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('orders')
            ->where('delivery_type', 'delivery')
            ->get();
                return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $dropdown = '
                        <div class="dropdown">
                            <a class="text-dark" href="#" role="button" id="dropdownMenu' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i> <!-- 3-dot icon -->
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu' . $row->id . '">
                                <li>
                                    <a class="dropdown-item vieworder" href="' . route('delivery_option.show', ['id' => $row->id]) . '" data-id="' . $row->id . '">Order Detail</a>
                                </li>
                                <li>
                                    <a class="dropdown-item confirm" href="' . route('delivery_option.confirm', ['id' => $row->id]) . '">Confirm Order</a>
                                </li>
                            </ul>
                        </div>';
                    return $dropdown;
                })
                ->rawColumns(['action'])
                ->make(true);
                }
        }
        public function show($id)
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
            ])
            ->get();

        $delivery = Delivery::find($order->delivery_id);
        $payment = DB::table('payment')
            ->where('order_id', $order->id)
            ->select('payment_type')
            ->first();

        return view('Admin.delivery.order_detail', compact('order', 'orderItems', 'delivery', 'payment'));
    }
    public function invoice($id)
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
            ])
            ->get();

        $delivery = Delivery::find($order->delivery_id);
        $payment = DB::table('payment')
            ->where('order_id', $order->id)
            ->select('payment_type')
            ->first();

        return view('Admin.delivery.delivery_processing', compact('order', 'orderItems', 'delivery', 'payment'));
    }

public function confirm(Request $request, $id)
{
    try {
        $order = Order::findOrFail($id);

        // Fetch the delivery record (if it exists)
        $delivery = \App\Models\Deliveries::where('order_id', $order->id)->first();
        payment::where('order_id', $order->id)
            ->update(['payment_status' => 'paid']);

        $data = [
            'order_number'   => $order->order_num,
            'delivery_date'  => optional($order->created_at)->format('Y-m-d'),
            'recipient_name' => $order->guest_name,
            'address'        => $order->guest_address,
            'phone'          => $order->phone_guest,
            'status'         => $order->status,
            'order_id'       => $order->id,
            'delivery_image' => $delivery ? asset('storage/' . $delivery->delivery_image) : null,
            'notes'          => $delivery?->noted,
            'delivered'      => $delivery ? true : false
        ];
        

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery data loaded.',
                'data'    => $data
            ]);
        }

        return view('Admin.delivery.confirm_order', compact('data', 'order', 'delivery'));

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

        return back()->with('error', 'Something went wrong.');
    }
}

public function store(Request $request)
{
    $request->validate([
        'delivery_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'noted' => 'nullable|string|max:500',
    ]);

    $delivery = new deliveries();
    $delivery->order_id = $request->order_id;
    // $delivery->delivery_status = $request->delivery_status;
    $delivery->customer_name = $request->customer_name;
    if ($request->hasFile('delivery_image')) {
        $imagePath = $request->file('delivery_image')->store('deliveries', 'public');
        $delivery->delivery_image = $imagePath;
    }
    $order = Order::find($request->order_id);
    if ($order) {
        $order->status = 'completed';
        $order->save();
    }
    $delivery->noted = $request->delivery_notes;
    $delivery->save();

    return redirect()->route('delivery_option.index')->with('success', 'Delivery created successfully.');


}

    // public function show_pickup($id)
    //     {
    //     $order = Order::findOrFail($id);

    //     $orderItems = DB::table('order_item')
    //         ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
    //         ->where('order_item.order_id', $order->id)
    //         ->select([
    //             'order_item.quantity',
    //             'order_item.price',
    //             'product_item.product_name',
    //             'product_item.images as imgSrc',
    //             'product_item.color_code',
    //             'product_item.size',
    //         ])
    //         ->get();

    //     $delivery = Delivery::find($order->delivery_id);
    //     $payment = DB::table('payment')
    //         ->where('order_id', $order->id)
    //         ->select('payment_type')
    //         ->first();

    //     return view('Admin..order_detail', compact('order', 'orderItems', 'delivery', 'payment'));
    // }
}