<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        if ($product->stock < $validated['quantity']) {
            return redirect()->back()->with('error', 'Not enough stock available');
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Not enough stock available');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity']
            ]);
        }

        $this->updateCartTotal($cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.id' => 'required|exists:cart_items,id',
            'cart_items.*.quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', auth()->id())->first();
        
        foreach ($validated['cart_items'] as $item) {
            $cartItem = $cart->cartItems()->findOrFail($item['id']);
            $product = $cartItem->product;
            
            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', "Not enough stock available for {$product->name}");
            }
            
            $cartItem->update(['quantity' => $item['quantity']]);
        }

        $this->updateCartTotal($cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        $cart = Cart::where('user_id', auth()->id())->first();
        $cart->cartItems()->where('id', $validated['cart_item_id'])->delete();
        
        $this->updateCartTotal($cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->cartItems()->delete();
            $cart->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }

    private function updateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart->cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }
        $cart->update(['total_amount' => $total]);
    }
} 