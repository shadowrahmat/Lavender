<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $heroProducts = Product::with('category')
            ->where('is_active', true)
            ->where('show_on_hero', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'description' => $c->description,
                'icon' => $c->icon,
                'image_url' => $c->image_url,
                'products_count' => $c->products()->where('is_active', true)->count(),
            ]);

        $popularProducts = Product::with('category')
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(6)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return Inertia::render('Home', [
            'heroProducts'     => $heroProducts,
            'featuredProducts' => $featuredProducts,
            'categories'       => $categories,
            'popularProducts'  => $popularProducts,
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About');
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }

    public function corporate(): Response
    {
        return Inertia::render('Corporate');
    }

    public function faq(): Response
    {
        return Inertia::render('Faq');
    }

    public function privacy(): Response
    {
        return Inertia::render('PrivacyPolicy');
    }

    public function terms(): Response
    {
        return Inertia::render('Terms');
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'final_price' => $product->final_price,
            'discount_percentage' => $product->discount_percentage,
            'featured_image_url' => $product->featured_image_url,
            'short_description' => $product->short_description,
            'category' => [
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ],
            'stock' => $product->stock,
            'is_featured' => $product->is_featured,
        ];
    }
}
