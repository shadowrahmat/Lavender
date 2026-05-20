<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    private function getCartIdentifier(Request $request): array
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }
        return ['session_id' => $request->session()->getId()];
    }

    private function getCartItems(Request $request): array
    {
        $identifier = $this->getCartIdentifier($request);
        $items = Cart::with('product.category')
            ->where($identifier)
            ->get();

        $subtotal = 0;
        $formatted = $items->map(function ($item) use (&$subtotal) {
            $price = $item->product->final_price;
            $total = $price * $item->quantity;
            $subtotal += $total;
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'slug' => $item->product->slug,
                'image' => $item->product->featured_image_url,
                'price' => $price,
                'quantity' => $item->quantity,
                'total' => $total,
                'stock' => $item->product->stock,
                'category' => $item->product->category->name,
            ];
        });

        $deliveryCharge = $subtotal > 500 ? 0 : 60;
        $total = $subtotal + $deliveryCharge;

        return [
            'items' => $formatted,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'total' => $total,
            'count' => $items->sum('quantity'),
        ];
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Cart', [
            'cart' => $this->getCartItems($request),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $identifier = $this->getCartIdentifier($request);
        $cartItem = Cart::where($identifier)->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Cannot add more than available stock.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            Cart::create(array_merge($identifier, [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]));
        }

        return back();
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:20']);

        $identifier = $this->getCartIdentifier($request);
        $ownerKey = array_key_first($identifier);
        if ($cart->$ownerKey !== $identifier[$ownerKey]) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, Cart $cart)
    {
        $identifier = $this->getCartIdentifier($request);
        $ownerKey = array_key_first($identifier);
        if ($cart->$ownerKey !== $identifier[$ownerKey]) {
            abort(403);
        }

        $cart->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $cartData = $this->getCartItems($request);
        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid($cartData['subtotal'])) {
            return back()->withErrors(['coupon' => 'Invalid or expired coupon code.']);
        }

        session(['applied_coupon' => $coupon->code]);
        return back()->with('success', 'Coupon applied!');
    }

    public function getCartJson(Request $request)
    {
        return response()->json($this->getCartItems($request));
    }
}
