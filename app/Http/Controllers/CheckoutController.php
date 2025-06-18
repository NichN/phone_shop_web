<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|json',
        ]);
        
        $cart = json_decode($request->cart_data, true);
        
        $subtotal = collect($cart)->sum(function ($item) {
            return floatval(preg_replace('/[^\d.]/', '', $item['price'])) * $item['quantity'];
        });
        
        $deliveryFee = Delivery::first()->fee;
        $totalAmount = $subtotal + $deliveryFee;
        
        return view('customer.checkout', [
            'orderItems' => $cart,
            'subtotal' => number_format($subtotal, 2),
            'deliveryFee' => number_format($deliveryFee, 2),
            'totalAmount' => number_format($totalAmount, 2),
            // Remove user registration options from view
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
            // Parse cart items
            $cartItems = json_decode($request->input('cart_data'), true);
            $subtotal = floatval(str_replace(',', '', $request->subtotal));
            $deliveryFee = floatval(str_replace(',', '', $request->delivery_fee));
            $totalAmount = floatval(str_replace(',', '', $request->total_amount));

            $order = Order::create([
                'user_id' => null, // Always null for guest checkout
                'delivery_id' => $request->delivery_id ?? 1,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'guest_name' => $request->guest_name,
                'guest_address' => $request->guest_address,
                'phone_guest' => $request->phone_guest,
                'status' => 'pending', // Add status field
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_item_id' => $item['id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => floatval(preg_replace('/[^\d.]/', '', $item['price'])),
                ]);
            }

            DB::commit();
            
            // Clear cart after successful checkout
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'order_id' => $order->id,
                    'redirect' => route('checkout.success', ['order' => $order->id])
                ]);
            }
            
            return redirect()->route('checkout.success', ['order' => $order->id]);

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
    
    public function checkoutSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('customer.checkout_success', compact('order'));
    }
}