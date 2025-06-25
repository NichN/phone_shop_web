<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CartController extends Controller
{
    public function storeCart(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'product_item_id' => 'required|integer|exists:product_item,id',
                'quantity' => 'nullable|integer|min:1'
            ]);

            $userId = Auth::id();
            $productItemId = $validated['product_item_id'];
            $quantity = $validated['quantity'] ?? 1;

            $existingCartItem = Cart::where('user_id', $userId)
                ->where('product_item_id', $productItemId)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_item_id' => $productItemId,
                    'quantity' => $quantity
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    public function countCart()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['cart_count' => $count]);
    }
    public function checkcart()
    {
        $userId = Auth::id();
        $cartItems = DB::table('cart')
            ->join('product_item', 'cart.product_item_id', '=', 'product_item.id')
            ->join('product', 'product_item.pro_id', '=', 'product.id')
            ->where('cart.user_id', $userId)
            ->select(
                'cart.id as cart_id',
                'cart.quantity',
                'product_item.id as product_item_id',
                'product_item.product_name as name',
                'product_item.images',
                'product_item.color_code',
                'product_item.size',
                'product_item.price'
            )
            ->get()
            ->map(function ($item) {
            $images = json_decode($item->images, true) ?? [];
            $firstImage = count($images) > 0
                ? asset('storage/' . $images[0])
                : asset('default-image.jpg');

            return [
                'id' => $item->cart_id,
                'quantity' => $item->quantity,
                'name' => $item->name,
                'price' => $item->price,
                'images' => $firstImage,
                'color' => $item->color_code,
                'size' => $item->size,
            ];
        });

        $total = DB::table('cart')
            ->join('product_item', 'cart.product_item_id', '=', 'product_item.id')
            ->where('cart.user_id', $userId)
            ->select(DB::raw('SUM(cart.quantity * product_item.price) as total_price'))
            ->value('total_price');

        return response()->json([
            'cartItems' => $cartItems,
            'total' => $total ?? 0
        ]);
    }
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart,id',
        ]);
        $cart = Cart::where('id', $request->id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $cart->delete();
        return response()->json(['success' => true]);
    }
}
