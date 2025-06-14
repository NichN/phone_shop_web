<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\cart;
use App\Models\Product;
use App\Models\productdetail;
use Illuminate\Validation\Rules\Can;

class CartController extends Controller
{
    public function storeCart(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'product_id' => 'required|exists:product,id',
            ]);
            $product = Product::findOrFail($request->product_id);
            $existingCartItem = Cart::where('user_id', Auth::id())
                                ->where('product_id', $request->product_id)
                                ->first();
            if ($existingCartItem) {
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => 1
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
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
        $count = Cart::where('user_id', Auth::id())->count();
        return response()->json(['cart_count' => $count]);
    }
    public function checkcart()
    {
        $userId = Auth::id();
        $cartItems = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 
                        where pi2.pro_id = product.id and pi2.stock > 0
                    )');
            })
            ->where('cart.user_id', $userId)
            ->select('cart.quantity', 'product.name', 'product_item.price', 'product_item.images')
            ->get()
            ->map(function ($item) {
                $images = json_decode($item->images);
                $firstImage = $images && count($images) > 0 
                            ? asset('storage/' . $images[0]) 
                            : asset('default-image.jpg');

                return [
                    'quantity' => $item->quantity,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image' => $firstImage,
                ];
            });
        $total = DB::table('cart')
            ->join('product_item', function ($join) {
                $join->on('cart.product_id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 
                        where pi2.pro_id = cart.product_id and pi2.stock > 0
                    )');
            })
            ->where('cart.user_id', $userId)
            ->select(DB::raw('SUM(cart.quantity * product_item.price) as total_price'))
            ->value('total_price');

        return response()->json([
            'cartItems' => $cartItems,
            'total' => $total ?? 0
        ]);
    }
    public function remove($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
        }
        $cart->delete();
        return response()->json(['success' => true]);
    }
}