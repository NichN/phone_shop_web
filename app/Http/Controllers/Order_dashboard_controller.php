<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;


class Order_dashboard_controller extends Controller
{
    

public function index()
    {
        $totalOrder = Order::count();
        $totalCanceled = Order::where('status', 'cancelled')->count();
        $totalProcessing = Order::where('status', 'processing')->count();
        $totalCompleted = Order::where('status', 'completed')->count();
        $totalPending = Order::where('status', 'pending')->count();
        $totalIncome = Order::where('status', 'completed')->sum('total_amount');

        $query = Order::query();

        if (request()->input('date')) {
            $query->whereDate('created_at', request()->input('date'));
        }
        if (request()->input('guest_name')) {
            $query->where('guest_name', request()->input('guest_name'));
        }
        if (request()->input('order_id')) {
            $query->where('order_num', request()->input('order_id'));
        }
        if (request()->input('delivery_method')) {
            $query->where('delivery_type', request()->input('delivery_method'));
        }
        if (request()->input('status')) {
            $query->where('status', request()->input('status'));
        }

        $filteredTotalOrder = (clone $query)->count();
        $filteredTotalCanceled = (clone $query)->where('status', 'cancelled')->count();
        $filteredTotalProcessing = (clone $query)->where('status', 'processing')->count();
        $filteredTotalCompleted = (clone $query)->where('status', 'completed')->count();
        $filteredTotalPending = (clone $query)->where('status', 'pending')->count();
        $filteredTotalIncome = (clone $query)->where('status', 'completed')->sum('total_amount');

        $orders = $query->get();

        return view('Admin.order.index', [
            'total_order' => $totalOrder,
            'total_canceled' => $totalCanceled,
            'total_processing' => $totalProcessing,
            'total_completed' => $totalCompleted,
            'total_income' => $totalIncome,
            'total_pending' => $totalPending,
            'filtered_total_order' => $filteredTotalOrder,
            'filtered_total_canceled' => $filteredTotalCanceled,
            'filtered_total_processing' => $filteredTotalProcessing,
            'filtered_total_completed' => $filteredTotalCompleted,
            'filtered_total_income' => $filteredTotalIncome,
            'filtered_total_pending' => $filteredTotalPending,
            'orders' => $orders,
        ]);
    }

    public function getData(Request $request)
    {
        $currentMonth = $request->input('current_month', now()->month);
        $currentYear = $request->input('current_year', now()->year);

        $query = Order::select('orders.*', 'payment.payment_type')
            ->leftJoin('payment', 'payment.order_id', '=', 'orders.id')
            ->whereMonth('orders.created_at', $currentMonth)
            ->whereYear('orders.created_at', $currentYear);

        // Apply filter parameters if they exist
        if ($request->filled('date')) {
            $query->whereDate('orders.created_at', $request->input('date'));
        }
        if ($request->filled('guest_name')) {
            $query->where('orders.guest_name', $request->input('guest_name'));
        }
        if ($request->filled('order_id')) {
            $query->where('orders.order_num', $request->input('order_id'));
        }
        if ($request->filled('delivery_method')) {
            $query->where('orders.delivery_type', $request->input('delivery_method'));
        }
        if ($request->filled('status')) {
            $query->where('orders.status', $request->input('status'));
        }

        return DataTables::eloquent($query)
        ->addColumn('action', function ($order) {
            $buttons = '
                <div class="dropdown">
                    <a class="text-dark" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="' . route('order_dashboard.order_detail', $order->id) . '">
                                <i class="fa fa-eye text-primary"></i> View
                            </a>
                        </li>';

                        // Show Accept button ONLY if payment is NOT processing
                        if ($order->status == 'pending') {
                            $buttons .= '
                                    <li>
                                        <form action="' . route('checkout.accept', $order->id) . '" method="POST">
                                            ' . csrf_field() . '
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="fas fa-check-circle"></i> Accept
                                            </button>
                                        </form>
                                    </li>';
                        }
                        $buttons .= '
                            <li>
                                <form action="' . route('checkout.decline', $order->id) . '" method="POST">
                                    ' . csrf_field() . '
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-times-circle"></i> Decline
                                    </button>
                                </form>
                            </li>';

                // Show Confirm button if payment_type = 'kh_qr'
                    if ($order->status  == 'processing') {
                        $buttons .= '
                            <li>
                                <form action="' . route('checkout.confirm', $order->id) . '" method="POST">
                                    ' . csrf_field() . '
                                    <button type="submit" class="dropdown-item text-warning">
                                        <i class="fas fa-check"></i> Finish Order
                                    </button>
                                </form>
                            </li>';
                    }
                    $buttons .= '</ul></div>';

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);

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

        return view('Admin.order.order_detail', compact('order', 'orderItems', 'payment'));
    }
}



