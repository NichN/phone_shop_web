<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        $orderItems = [
            [
                'name' => 'iPhone 16',
                'category' => 'Mobile Phone',
                'price' => '1299.00',
                'image' => 'Iphone16.jpg',
            ],
            [
                'name' => 'OPPO',
                'category' => 'Mobile Phone',
                'price' => '799.00',
                'image' => 'oppo.jpg',
            ],
            [
                'name' => 'OPPO',
                'category' => 'Mobile Phone',
                'price' => '899.00',
                'image' => 'oppo1.jpg',
            ],
        ];

        // Calculate totals
        $subtotal = array_sum(array_column($orderItems, 'price'));
        $deliveryFee = 1.50;
        $totalAmount = $subtotal + $deliveryFee;

        return view('customer.checkout', compact('orderItems', 'subtotal', 'deliveryFee', 'totalAmount'));
    }

    public function showCardPayment()
    {
        $totalAmount = 1397.00;

        return view('customer.card', compact('totalAmount'));
    }

    public function processPayment(Request $request)
    {
        // Here, you can handle the payment processing logic

        // For now, let's just return a response that shows the form data
        return response()->json([
            'card_number' => $request->input('card_number'),
            'expiry_date' => $request->input('expiry_date'),
            'cvv' => $request->input('cvv'),
            'cardholder_name' => $request->input('cardholder_name'),
            'total_amount' => $request->input('total_amount'), // Add the total amount to the form
        ]);
    }


    // public function processCheckout(Request $request)
    // {
    //     $validated = $request->validate([
    //         'phone' => 'required|digits:10', 
    //         'address' => 'required|string|max:255',
    //         'payment_method' => 'required|in:1,2',
    //     ]);

    //     return redirect()->route('checkout.success')->with('success', 'Your order has been placed!');
    // }

    // public function success()
    // {
    //     return view('order_success');
    // }
}

// class CheckoutController extends Controller
// {
//     public function process(Request $request)
//     {
//         // Step 1: Validate input
//         $validated = $request->validate([
//             'phone_number' => 'required|string',
//             'address' => 'required|string',
//             'payment_method' => 'required|in:cash,card',
//             'cart_items' => 'required|array',
//             'cart_items.*.product_id' => 'required|integer',
//             'cart_items.*.quantity' => 'required|integer|min:1',
//             'cart_items.*.price' => 'required|numeric|min:0',
//             'subtotal' => 'required|numeric',
//             'delivery_fee' => 'required|numeric',
//             'total_amount' => 'required|numeric',
//         ]);

//         // Step 2: Get data
//         $user_id = Auth::id(); // Assumes user is logged in
//         $cartItems = $request->input('cart_items');
//         $subtotal = $request->input('subtotal');
//         $deliveryFee = $request->input('delivery_fee');
//         $totalAmount = $request->input('total_amount');

//         // Step 3: Create order and order items inside a transaction
//         DB::beginTransaction();

//         try {
//             $order = Order::create([
//                 'user_id' => $user_id,
//                 'delivery_id' => 1, // you can customize this
//                 'subtotal' => $subtotal,
//                 'phone_number' => $request->input('phone_number'),
//                 'address' => $request->input('address'),
//                 'delivery_fee' => $deliveryFee,
//                 'total_amount' => $totalAmount,
//             ]);

//             foreach ($cartItems as $item) {
//                 OrderItem::create([
//                     'order_id' => $order->id,
//                     'quantity' => $item['quantity'],
//                     'price' => $item['price'],
//                 ]);
//             }

//             DB::commit();

//             // Step 4: Redirect based on payment method
//             if ($request->payment_method === 'cash') {
//                 return redirect()->route('payment.invoice')->with('success', 'Order placed successfully!');
//             } else {
//                 return redirect()->route('payment.card')->with('success', 'Proceed to payment.');
//             }

//         } catch (\Exception $e) {
//             DB::rollBack();
//             return back()->with('error', 'Checkout failed: ' . $e->getMessage());
//         }
//     }

//     public function showCheckout()
// {
//     // Load cart data here if needed
//     $orderItems = []; // Replace this with actual cart data
//     $subtotal = 0;
//     $deliveryFee = 1.5;
//     $totalAmount = $subtotal + $deliveryFee;

//     return view('checkout', compact('orderItems', 'subtotal', 'deliveryFee', 'totalAmount'));
// }

// }