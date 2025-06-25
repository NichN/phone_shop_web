<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\productdetail;
use App\Models\payment;
use App\Models\refund;

class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
{
    $request->validate([
        'cart_data' => 'required|json',
    ]);

    $cart = json_decode($request->cart_data, true);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Cart is empty.');
    }

    $subtotal = collect($cart)->sum(function ($item) {
        return floatval(preg_replace('/[^\d.]/', '', $item['price'])) * $item['quantity'];
    });

    $deliveryFee = Delivery::first()->fee ?? 0;
    $totalAmount = $subtotal + $deliveryFee;
    $user = auth()->user();
    // dd($user);
    $userId = $request->input('user_id');
    $isGuest = $request->has('is_guest');

    return view('customer.checkout', [
        'orderItems' => $cart,
        'subtotal' => number_format($subtotal, 2),
        'deliveryFee' => number_format($deliveryFee, 2),
        'totalAmount' => number_format($totalAmount, 2),
        'user' => $user,
        'isGuest' => $isGuest,
        'userId' => $userId,
    ]);
}
    public function storeCheckout(Request $request)
{
    $request->validate([
        'guest_name' => 'required|string|max:255',
        'guest_address' => 'required|string|max:500',
        'phone_guest' => 'required|string|max:20',
        'cart_data' => 'required|json',
    ]);

    DB::beginTransaction();

    try {
        $cartItems = json_decode($request->input('cart_data'), true);
        $subtotal = floatval(str_replace(',', '', $request->subtotal));
        $deliveryFee = floatval(str_replace(',', '', $request->delivery_fee));
        $totalAmount = floatval(str_replace(',', '', $request->total_amount));

        $latestOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = 1;

        if ($latestOrder && preg_match('/ORD-(\d+)/', $latestOrder->order_num, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        $orderNum = 'ORD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        $order = Order::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'order_num' => $orderNum,
            'delivery_id' => $request->delivery_id ?? 1,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'guest_name' => $request->guest_name,
            'guest_address' => $request->guest_address,
            'phone_guest' => $request->phone_guest,
            'status' => 'processing',
        ]);
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_item_id' => $item['id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => floatval(preg_replace('/[^\d.]/', '', $item['price'])),
            ]);
        }
        DB::table('deliveries')->insert([
            'order_id' => $order->id,
            'delivery_status' => 'processing',
            'customer_name' => $request->guest_name,
            'customer_phone' => $request->phone_guest,
            'address' => $request->guest_address,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect' => route('checkout.payment', ['order' => $order->id])
            ]);
        }

        return redirect()->route('checkout.payment', ['order' => $order->id]);

    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }

        return back()->withErrors('Checkout failed: ' . $e->getMessage());
    }
}

public function processPayment(Request $request)
{
    $latestOrder = DB::table('orders')
        ->where('user_id', auth()->id())
        ->where('status', 'processing')
        ->orderByDesc('id')
        ->first();
    if (!$latestOrder) {
        return redirect()->route('customer.card')->with('error', 'No pending order found.');
    }
    $orderItems = DB::table('order_item')
        ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
        ->join('orders', 'order_item.order_id', '=', 'orders.id')
        ->where('order_item.order_id', $latestOrder->id)
        ->select([
            'order_item.order_id',
            'product_item.product_name as title',
            'product_item.images as imgSrc',
            'order_item.quantity',
            'order_item.price',
            'product_item.color_code',
            'product_item.size',
            'orders.subtotal',
            'orders.delivery_fee',
            'orders.total_amount',
        ])
        ->get();
        // dd($orderItems);
    return view('customer.card', compact('orderItems'));
}
public function storePayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'payment_type' => 'required|string|max:50',
    ]);

    DB::beginTransaction();
    try {
        $order = Order::findOrFail($request->order_id);
        // $order->status = 'paid';
        $order->save();
        payment::create([
            'order_id'     => $order->id,
            'payment_type' => $request->payment_type,
            'remark'       => $request->input('note', ''),
            'amount'       => $order->total_amount,
        ]);
        $orderItems = DB::table('order_item')
            ->where('order_id', $order->id)
            ->get();
        foreach ($orderItems as $item) {
            DB::table('product_item')->where('id', $item->product_item_id)->decrement('stock', $item->quantity);
        }
        DB::commit();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment processed and stock updated.',
            ]);
        }

    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->withErrors('Payment failed: ' . $e->getMessage());
    }
}

public function orderHistory(Request $request)
{
    $orders = Order::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('customer.history', compact('orders'));
}

public function orderDetails($id)
{
    $order = Order::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

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

    return view('customer.order_detail', compact('order', 'orderItems', 'delivery', 'payment'));
}
public function returns($id)
{
    $order = Order::with('items')->findOrFail($id);
    return view('customer.history', compact('order'));
}
public function processReturn(Request $request, $id)
{
    $request->validate([
        'return_reason' => 'required|string|max:1000',
    ]);

    refund::create([
        'order_id' => $id,
        'return_status' => 'requested',
        'return_reason' => $request->return_reason,
        'refund_amount' => 0,
    ]);
    Order::where('id', $id)->update([
        'status' => 'cancelled',
    ]);

    return redirect()->route('checkout.history')->with('success', 'Return request submitted.');
}
}