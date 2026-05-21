<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\SitePage;
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
        $faqs = Faq::active()->get()->map(fn($f) => [
            'id'       => $f->id,
            'question' => $f->question,
            'answer'   => $f->answer,
        ]);

        return Inertia::render('Faq', ['faqs' => $faqs]);
    }

    public function privacy(): Response
    {
        $page = SitePage::findBySlug('privacy');

        return Inertia::render('PrivacyPolicy', ['page' => $page ? [
            'title'        => $page->title,
            'last_updated' => $page->last_updated,
            'sections'     => $page->sections ?? [],
        ] : null]);
    }

    public function terms(): Response
    {
        $page = SitePage::findBySlug('terms');

        return Inertia::render('Terms', ['page' => $page ? [
            'title'        => $page->title,
            'last_updated' => $page->last_updated,
            'sections'     => $page->sections ?? [],
        ] : null]);
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
