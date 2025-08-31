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
use Illuminate\Support\Str;
use App\Mail\CustomerVerificationCodeMail;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

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
        $deliveryMethod = $request->input('delivery_type', 'delivery');
        if ($deliveryMethod == 'pick up') {
            $totalAmount = $subtotal;
        } else {
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
            'phone_guest' => 'required|string|max:20',
            'email_guest' => 'required|email|max:255',
            'cart_data' => 'required|json',
        ]);

        DB::beginTransaction();

        try {
            $cartItems = json_decode($request->input('cart_data'), true);

            $subtotal = floatval(str_replace(',', '', $request->subtotal));
            $deliveryFee = floatval(str_replace(',', '', $request->delivery_fee));
            // $totalAmount = floatval(str_replace(',', '', $request->total_amount));
            $delivery_type = $request->input('delivery_method');

            if ($delivery_type == "delivery") {
                $totalAmount = $subtotal + $deliveryFee;
            } else {
                $totalAmount = $subtotal;
            }
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
                'guest_token' => Str::uuid(),
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
            // $orderItems = DB::table('order_item')
            //     ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
            //     ->where('order_item.order_id', $order->id)
            //     ->select([
            //         'product_item.product_name',
            //         'product_item.color_code',
            //         'product_item.size',
            //         'order_item.quantity',
            //         'order_item.price',
            //         'product_item.images'
            //     ])
            //     ->get();
            // $adminEmails = \App\Models\User::where('role_id', '1')->pluck('email')->toArray();

            // \Mail::to($adminEmails)->send(
            //     new \App\Mail\OrderConfirmationMail($order, $orderItems)
            // );

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect' => route('checkout.payment', ['orderId' => $order->id]),
                ]);
            }

            return redirect()->route('checkout.payment', ['orderId' => $order->id]);
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
    public function processPayment($orderId)
    {
        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found or access denied.');
        }

        // Get items for the selected order
        $orderItems = DB::table('order_item')
            ->join('product_item', 'order_item.product_item_id', '=', 'product_item.id')
            ->join('orders', 'order_item.order_id', '=', 'orders.id')
            ->where('order_item.order_id', $order->id)
            ->select([
                'order_item.order_id',
                'product_item.product_name as title',
                'product_item.images as imgSrc',
                'order_item.quantity',
                'order_item.price',
                'product_item.color_code',
                'product_item.size',
                'orders.subtotal',
                'orders.delivery_fee as fee'
            ])
            ->get();

        return view('customer.card', [
            'orderItems' => $orderItems,
            'deliveryType' => $order->delivery_type,
        ]);
    }
    public function storePayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'payment_type' => 'required|string|max:50',
        'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    DB::beginTransaction();

    try {
        $order = Order::findOrFail($request->order_id);

        $imgPath = null;
        $imgFullPath = null;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $imgPath = $file->store('payment_proofs', 'public');
            $imgFullPath = storage_path('app/public/' . $imgPath);
        }

        payment::create([
            'order_id' => $order->id,
            'payment_type' => $request->payment_type,
            'remark' => $request->input('note', ''),
            'amount' => $order->total_amount,
            'img_verify' => $imgPath,
            'payment_status' => 'pending',
        ]);
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
        $adminEmails = \App\Models\User::where('role_id', '1')->pluck('email')->toArray();

            \Mail::to($adminEmails)->send(
                new \App\Mail\OrderConfirmationMail($order, $orderItems)
            );

        DB::commit();

        if ($request->payment_type === 'online_payment') {
            $telegramBotToken = '8108484660:AAFfEtec51wHSAJfHso1BTT6X9_H5YfcMIo';
            $telegramChatId = '@ksaranauniyear4';

            $caption = "🛒 *New Order Received!*\n"
                . "Order ID: *{$order->order_num}*\n"
                . "Payment Type: _online_payment_\n"
                . "Note: " . ($request->note ?? 'None') . "\n"
                . "Status: *Pending Payment Confirmation*\n\n"
                . "👉 [Review Order in Dashboard](http://127.0.0.1:8000/order_dashboard)";


            if ($imgFullPath && file_exists($imgFullPath)) {
                $response = Http::attach(
                    'photo', file_get_contents($imgFullPath), basename($imgFullPath)
                )->post("https://api.telegram.org/bot{$telegramBotToken}/sendPhoto", [
                    'chat_id' => $telegramChatId,
                    'caption' => $caption,
                ]);

                if (!$response->ok()) {
                    \Log::error('Telegram sendPhoto failed', ['response' => $response->body()]);
                }
            } else {
                // fallback text message if no image
                Http::get("https://api.telegram.org/bot{$telegramBotToken}/sendMessage", [
                    'chat_id' => $telegramChatId,
                    'text' => $caption,
                ]);
            }
        }

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
        $query = Order::query();

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $guestEmail = $request->query('guest_eamil'); 
            $guestToken = $request->query('order_num');

            if (!$guestEmail || !$guestToken) {
                return view('customer.history', [
                    'orders' => collect(),
                    'error' => 'Please provide both email and order number.'
                ]);
            }

            $query->where('guest_eamil', $guestEmail)
                ->where('order_num', $guestToken);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_num', 'like', "%{$search}%")
                    ->orWhere('guest_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('customer.history', [
            'orders' => $orders,
            'isGuest' => !auth()->check()
        ]);
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
        // deliveries::where('order_id', $id)->update([
        //     'delivery_status' => 'cancelled',
        // ]);

        return redirect()->route('checkout.history')->with('success', 'Return request submitted.');
    }
    public function acceptOrder(Order $order)
    {
        if ($order->status === 'pending') {
            $code = rand(100000, 999999);

            $order->code_verify = $code;
            $order->status = 'accepted';
            $order->save();

            $orderItems = OrderItem::where('order_id', $order->id)->get();

            foreach ($orderItems as $item) {
                productdetail::where('id', $item->product_item_id)
                    ->decrement('stock', $item->quantity);
            }

            \Mail::to($order->guest_eamil)->send(
                new \App\Mail\CustomerVerificationCodeMail($order, $code)
            );
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Order accepted, stock decremented, and verification code sent.'], 200);
        }

        return redirect('/order_dashboard')->with('success', 'Order accepted, stock decremented, and verification code sent.');
    }

    // public function declineOrder(Order $order)
    // {
    //     if ($order->status === 'pending') {
    //         \Mail::to($order->guest_eamil)->send(new \App\Mail\OrderDeclinedMail($order));

    //         $order->delete();
    //     }

    //     if (request()->ajax()) {
    //         return response()->json(['message' => 'Order declined and deleted.'], 200);
    //     }

    //     return redirect('/order_dashboard')->with('success', 'Order declined and deleted.');
    // }
    public function declineOrder(Order $order)
{
    // Check if already cancelled
    if ($order->status === 'cancelled') {
        if (request()->ajax()) {
            return response()->json(['message' => 'Order is already cancelled.'], 200);
        }
        return redirect('/order_dashboard')->with('info', 'Order is already cancelled.');
    }

    if ($order->status === 'pending') {
        // Just cancel and notify
        $order->status = 'cancelled';
        $order->save();

        \Mail::to($order->guest_eamil)->send(new \App\Mail\OrderDeclinedMail($order));
    } elseif ($order->status === 'accepted') {
        // Restore stock
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $item) {
            productdetail::where('id', $item->product_item_id)
                ->increment('stock', $item->quantity);
        }

        $order->status = 'cancelled';
        $order->save();

        \Mail::to($order->guest_eamil)->send(new \App\Mail\OrderDeclinedMail($order));
    }

    if (request()->ajax()) {
        return response()->json(['message' => 'Order declined and status updated.'], 200);
    }

    return redirect('/order_dashboard')->with('success', 'Order declined and status updated.');
}


    // public function verifyCode(Request $request)
    // {
    //     $request->validate([
    //         'order_id' => 'required|integer',
    //     ]);

    //     $order = \App\Models\Order::find($request->order_id);

    //     if (!$order) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order not found.'
    //         ]);
    //     }

    //     if ($order->code_verify !== $request->code) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid verification code.'
    //         ]);
    //     }
    //     $order->status = 'accepted';
    //     $order->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Code verified successfully.',
    //         'order_id' => $order->id
    //     ]);
    // }
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

    // Skip if already accepted or completed
    if (in_array($order->status, ['accepted', 'approved', 'completed'])) {
        return response()->json([
            'success' => true,
            'message' => 'Order already verified.',
            'order_id' => $order->id
        ]);
    }

    // Just update the status (no code check)
    $order->status = 'accepted';
    $order->save();

    // Optional: send confirmation email to customer
    // Mail::to($order->guest_eamil ?? $order->user->email)->send(new \App\Mail\OrderAcceptedMail($order));

    return response()->json([
        'success' => true,
        'message' => 'Order has been accepted successfully.',
        'order_id' => $order->id
    ]);
}


    public function confirmPayment(Order $order)
    {
        $order->status = 'Confirmed';
        $order->save();
        if ($order->guest_eamil) {
            Mail::to($order->guest_eamil)->send(new PaymentConfirmed($order));
        }
        return response()->noContent();
    }
    // public function declinePayment(Order $order)
    // {
    //     if ($order->status === 'processing') {
    //         \Mail::to($order->guest_eamil)->send(new \App\Mail\OrderDeclinedMail($order));

    //         $order->delete();
    //     }

    //     if (request()->ajax()) {
    //         return response()->json(['message' => 'Order declined and deleted.'], 200);
    //     }

    //     return redirect('/order_dashboard')->with('success', 'Order declined and deleted.');
    // }
}
