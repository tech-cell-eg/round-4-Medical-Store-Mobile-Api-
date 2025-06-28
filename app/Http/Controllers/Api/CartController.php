<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        Log::info('Cart session data: ', ['cart' => $cart]); // Log cart content
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return response()->json([
            'cart' => $cart,
            'total' => $total,
            'items_count' => count($cart)
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->product_id] = [
                'name' => $product->name,
                'price' => $product->price ?? 0.00, // Fallback if price is null
                'quantity' => $request->quantity,
                'image' => $product->image_url ?? 'default.jpg', // Fallback if image_url is null
            ];
        }

        session()->put('cart', $cart);
        Log::info('Cart after add: ', ['cart' => $cart]); // Log after adding
        return response()->json(['message' => 'Product added to cart', 'cart' => $cart]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Cart updated', 'cart' => $cart]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Product removed from cart', 'cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paypal'
        ]);

        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $subtotal = $total + 28.80;
        $discount = 28.80 + 15.00;
        $finalTotal = $total;

        return response()->json([
            'cart' => $cart,
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
        $orderId = '9d56' . rand(1000, 9999);
        session()->forget('cart');
        return response()->json([
            'order_id' => $orderId,
            'tracking_info' => 'Track the delivery in the order section'
        ], 201);
    }
}