<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(Request $request): Response
    {
        $identifier = Auth::check()
            ? ['user_id' => Auth::id()]
            : ['session_id' => $request->session()->getId()];

        $cartItems = Cart::with('product')->where($identifier)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        $deliveryCharge = $subtotal > 500 ? 0 : 60;
        $discount = 0;

        $couponCode = session('applied_coupon');
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal + $deliveryCharge - $discount;

        $userAddress = null;
        if (Auth::check()) {
            $userAddress = Auth::user()->addresses()->where('is_default', true)->first();
        }

        return Inertia::render('Checkout', [
            'cart' => [
                'items' => $cartItems->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'image' => $item->product->featured_image_url,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->final_price * $item->quantity,
                ]),
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $couponCode,
            ],
            'userAddress' => $userAddress,
            'user' => Auth::check() ? [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone,
            ] : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|in:cod,bkash,nagad',
            'notes' => 'nullable|string|max:500',
        ]);

        $identifier = Auth::check()
            ? ['user_id' => Auth::id()]
            : ['session_id' => $request->session()->getId()];

        $cartItems = Cart::with('product')->where($identifier)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        $deliveryCharge = $subtotal > 500 ? 0 : 60;
        $discount = 0;

        $couponCode = session('applied_coupon');
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal + $deliveryCharge - $discount;

        DB::transaction(function () use ($request, $cartItems, $subtotal, $deliveryCharge, $discount, $total, $coupon, $couponCode, $identifier) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_name' => Auth::check() ? null : $request->name,
                'guest_email' => Auth::check() ? null : $request->email,
                'guest_phone' => Auth::check() ? null : $request->phone,
                'delivery_address' => $request->address,
                'delivery_city' => $request->city,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $couponCode,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->featured_image_url,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->final_price * $item->quantity,
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            Cart::where($identifier)->delete();
            session()->forget('applied_coupon');

            session(['last_order_id' => $order->id, 'last_order_number' => $order->order_number]);
        });

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }

    public function success(): Response
    {
        $orderNumber = session('last_order_number');
        if (!$orderNumber) {
            return redirect()->route('home');
        }

        return Inertia::render('OrderSuccess', [
            'orderNumber' => $orderNumber,
        ]);
    }
}
