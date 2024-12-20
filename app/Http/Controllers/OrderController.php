<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $selectedItems = json_decode($request->input('selected_items'), true);
    
        $request->validate([
            'selected_items' => 'required',
            'total_price' => 'required|numeric|min:1',
        ]);
    
        if (!is_array($selectedItems)) {
            return back()->withErrors(['error' => 'Invalid selected items format.']);
        }
    
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);
    
        foreach ($selectedItems as $cartId) {
            $cart = Auth::user()->carts()->find($cartId);
            if ($cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
    
                $cart->delete();
            }
        }
    
        return redirect()->route('cart.index')->with('success', 'Checkout berhasil!');
    }
}
