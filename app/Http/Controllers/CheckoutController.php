<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        // Static order items data
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
        // You can pass any necessary data, like the total amount
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
