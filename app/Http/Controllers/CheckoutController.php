<?php

namespace App\Http\Controllers;

use App\Models\deliveries;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\productdetail;
use App\Models\payment;
use App\Models\refund;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerVerificationCodeMail;

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
    // dd($subtotal);

    $deliveryFee = Delivery::first()->fee ?? 0;
    $deliveryMethod = $request->input('delivery_method', 'delivery');
    if ($deliveryMethod = 'pick up') {
            $totalAmount = $subtotal;
        } else{
            $totalAmount = $subtotal + $deliveryFee;
        }
    $user = auth()->user();
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
        // 'guest_address' => 'required|string|max:500',
        'phone_guest' => 'required|string|max:20',
        'email_guest' => 'required|email|max:255',
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
            'guest_eamil' => $request->email_guest,
            'delivery_type' => $request->delivery_method,
            'rate_id' => 1,
            // 'code_verify'  =>$request ->code_verify,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_item_id' => $item['id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => floatval(preg_replace('/[^\d.]/', '', $item['price'])),
            ]);
        }
        $orderItems = DB::table('order_item')
            ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
            ->where('order_item.order_id', $order->id)
            ->select([
                'product_item.product_name',
                'product_item.color_code',
                'product_item.size',
                'order_item.quantity',
                'order_item.price',
                'product_item.images'
            ])
            ->get();
        \Mail::to('sreynichny220@gmail.com')->send(
            new \App\Mail\OrderConfirmationMail($order, $orderItems)
        );

        DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect' => route('checkout.payment', ['order' => $order->id]),
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
        

    $orderItems = DB::table('order_item')
        ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
        ->where('order_item.order_id', $latestOrder->id)
        ->select([
            'order_item.order_id',
            'product_item.product_name as title',
            'product_item.images as imgSrc',
            'order_item.quantity',
            'order_item.price',
            'product_item.color_code',
            'product_item.size',
        ])
        ->get();
    return view('customer.card', [
        'orderItems' => $orderItems,
        'deliveryType' => $latestOrder->delivery_type,
    ]);
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
        $order->save();
        payment::create([
            'order_id'     => $order->id,
            'payment_type' => $request->payment_type,
            'remark'       => $request->input('note', ''),
            'amount'       => $order->total_amount,
            'payment_status' => 'pending',
        ]);
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
    $order = Order::where('orders.id', $id)
    ->where('orders.user_id', auth()->id())
    ->join('exchange_rates', 'orders.rate_id', '=', 'exchange_rates.id')
    ->select('orders.*', 'exchange_rates.rate')
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
            'product_item.warranty'
        ])
        ->get();

    $delivery = Delivery::find($order->delivery_id);
    $payment = DB::table('payment')
        ->where('order_id', $order->id)
        ->select('payment_type')
        ->first();
    // dd($payment);

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
    deliveries::where('order_id', $id)->update([
        'delivery_status' => 'cancelled',
    ]);

    return redirect()->route('checkout.history')->with('success', 'Return request submitted.');
}
public function acceptOrder(Order $order)
{
    if ($order->status === 'pending') {
        // Generate verification code
        $code = rand(100000, 999999);

        $order->code_verify = $code;
        $order->status = 'accepted';
        $order->save();

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        foreach ($orderItems as $item) {
            productdetail::where('id', $item->product_item_id)
                ->decrement('stock', $item->quantity);
        }

        // Send verification email
        \Mail::to($order->guest_eamil)->send(
            new \App\Mail\CustomerVerificationCodeMail($order, $code)
        );
    }

    if (request()->ajax()) {
        return response()->json(['message' => 'Order accepted, stock decremented, and verification code sent.'], 200);
    }

    return redirect('/order_dashboard')->with('success', 'Order accepted, stock decremented, and verification code sent.');
}

 public function declineOrder(Order $order)
{
    if ($order->status === 'pending') {
        \Mail::to($order->guest_eamil)->send(new \App\Mail\OrderDeclinedMail($order));

        $order->delete();
    }

    if (request()->ajax()) {
        return response()->json(['message' => 'Order declined and deleted.'], 200);
    }

    return redirect('/order_dashboard')->with('success', 'Order declined and deleted.');
}

public function verifyCode(Request $request)
{
    $request->validate([
        'order_id' => 'required|integer',
    ]);

    $order = \App\Models\Order::find($request->order_id);

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found.'
        ]);
    }

    if ($order->code_verify !== $request->code) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ]);
    }
    $order->status = 'processing';
    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Code verified successfully.'
    ]);
}
}