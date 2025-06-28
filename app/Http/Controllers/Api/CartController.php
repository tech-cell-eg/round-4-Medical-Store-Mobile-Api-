<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Assume user authentication (e.g., via token); use a placeholder user_id for now
    private function getCart()
    {
        $userId = Auth::id(); // Replace with authenticated user ID (e.g., auth()->id())
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $items = $cart->items;
        $total = $items->sum(fn($item) => $item->price * $item->quantity);

        return response()->json([
            'cart' => $items->map->only(['id', 'product_id', 'quantity', 'price', 'image']),
            'total' => $total,
            'items_count' => $items->count()
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->getCart();

        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price ?? 0.00,
                'image' => $product->image_url ?? 'default.jpg',
            ]);
        }

        $items = $cart->items;
        return response()->json(['message' => 'Product added to cart', 'cart' => $items->map->only(['id', 'product_id', 'quantity', 'price', 'image'])]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = CartItem::findOrFail($id);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart = $this->getCart();
        return response()->json(['message' => 'Cart updated', 'cart' => $cart->items->map->only(['id', 'product_id', 'quantity', 'price', 'image'])]);
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        $cart = $this->getCart();
        return response()->json(['message' => 'Product removed from cart', 'cart' => $cart->items->map->only(['id', 'product_id', 'quantity', 'price', 'image'])]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paypal'
        ]);

        $cart = $this->getCart();
        $items = $cart->items;
        $total = $items->sum(fn($item) => $item->price * $item->quantity);
        $subtotal = $total + 28.80;
        $discount = 28.80 + 15.00;
        $finalTotal = $total;

        return response()->json([
            'cart' => $items->map->only(['id', 'product_id', 'quantity', 'price', 'image']),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $finalTotal,
            'shipping' => 'Free',
            'address' => $request->address,
            'payment_method' => $request->payment_method
        ]);
    }

    public function success(Request $request)
    {
        $cart = $this->getCart();
        $cart->items()->delete(); // Clear cart items
        $orderId = '9d56' . rand(1000, 9999);

        return response()->json([
            'order_id' => $orderId,
            'tracking_info' => 'Track the delivery in the order section'
        ], 201);
    }
}