<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $cartCount = 0;
        $wishlistCount = 0;

        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $cartCount = Cart::where('session_id', $request->session()->getId())->sum('quantity');
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => Auth::check() ? [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                    'avatar' => Auth::user()->avatar,
                ] : null,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'cart_count' => $cartCount,
            'wishlist_count' => $wishlistCount,
        ]);
    }
}
