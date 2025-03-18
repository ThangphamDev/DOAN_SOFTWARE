<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        return view('orders.create', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string'
        ]);

        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $cart->total_amount,
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_status'],
            'status' => 'pending'
        ]);

        foreach ($cart->cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);

            // Update product stock
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // Clear cart
        $cart->cartItems()->delete();
        $cart->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'payment_status' => 'required|string'
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }
} 