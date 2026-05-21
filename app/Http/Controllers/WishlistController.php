<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    public function index(): Response
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(fn($w) => [
                'id' => $w->id,
                'product' => [
                    'id' => $w->product->id,
                    'name' => $w->product->name,
                    'slug' => $w->product->slug,
                    'price' => $w->product->price,
                    'discount_price' => $w->product->discount_price,
                    'final_price' => $w->product->final_price,
                    'discount_percentage' => $w->product->discount_percentage,
                    'featured_image_url' => $w->product->featured_image_url,
                    'stock' => $w->product->stock,
                    'category' => ['name' => $w->product->category->name],
                ],
            ]);

        return Inertia::render('Account/Wishlist', ['wishlists' => $wishlists]);
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $request->product_id]);
            $inWishlist = true;
            $message = 'Added to wishlist!';
        }

        return back()->with([
            'success' => $message,
            'wishlist_status' => ['product_id' => $request->product_id, 'in_wishlist' => $inWishlist],
        ]);
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) abort(403);
        $wishlist->delete();
        return back()->with('success', 'Removed from wishlist.');
    }
}
