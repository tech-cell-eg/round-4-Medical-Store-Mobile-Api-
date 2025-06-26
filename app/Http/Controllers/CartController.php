<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;

class CartController
{
    /**
     * عرض الكارت بتاع المستخدم
     * GET /carts
     */
    public function index()
    {
         $user = User::first();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        // $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'message' => 'No cart found',
                'data' => null,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cart retrieved successfully',
            'data' => new CartResource($cart),
            'errors' => null
        ], 200);
    }

    /**
     * إضافة عنصر جديد للكارت
     * POST /cart/items
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = User::first();
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'No user found',
            'data' => null,
            'errors' => ['user' => 'No users in database']
        ], 404);
    }

    $cart = Cart::firstOrCreate(
        ['user_id' => $user->id],
        ['shipping' => 0]
    );

    $cartItem = $cart->items()->where('product_id', $validated['product_id'])->first();

    if ($cartItem) {
        $cartItem->quantity += $validated['quantity'];
        $cartItem->save();
    } else {
        $cartItem = $cart->items()->create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);
    }

    $order_total = $cart->items->sum(function ($item) {
        return ($item->product->price - ($item->product->discount ?? 0)) * $item->quantity;
    });
    $cart->shipping = $order_total > 500 ? 0 : 20.00;
    $cart->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Item added to cart successfully',
        'data' => new CartItemResource($cartItem),
        'errors' => null
    ], 201);
}

    /**
     * تعديل كمية عنصر في الكارت
     * PATCH /cart/items/{cartItemId}
     */
    public function updateItem(Request $request, $cartItemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($cartItemId);

        // تحقق إن العنصر تابع لكارت المستخدم
        $user = User::first();
        if ($cartItem->cart->user_id !== $user->id) {
        // if ($cartItem->cart->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to cart item',
                'data' => null,
                'errors' => ['cart_item' => 'You do not own this cart item']
            ], 403);
        }

        // لو الكمية 0، امسح العنصر
        if ($validated['quantity'] === 0) {
            $cartItem->delete();
            $message = 'Item removed from cart';
        } else {
            // حدّث الكمية
            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();
            $message = 'Item quantity updated successfully';
        }

        // تحديث shipping
        $cart = $cartItem->cart;
        $order_total = $cart->items->sum(function ($item) {
            return ($item->product->price - ($item->product->discount ?? 0)) * $item->quantity;
        });
        $cart->shipping = $order_total > 500 ? 0 : 20.00;
        $cart->save();

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => new CartItemResource($cartItem),
            'errors' => null
        ], 200);
    }

    /**
     * حذف عنصر من الكارت
     * DELETE /cart/items/{cartItemId}
     */
    public function destroyItem($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        // تحقق إن العنصر تابع لكارت المستخدم
            $user = User::first();
        if ($cartItem->cart->user_id !== $user->id) {
        // if ($cartItem->cart->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to cart item',
                'data' => null,
                'errors' => ['cart_item' => 'You do not own this cart item']
            ], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        // تحديث shipping
        $order_total = $cart->items->sum(function ($item) {
            return ($item->product->price - ($item->product->discount ?? 0)) * $item->quantity;
        });
        $cart->shipping = $order_total > 500 ? 0 : 20.00;
        $cart->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart successfully',
            'data' => null,
            'errors' => null
        ], 200);
    }
}
