<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('short_description', 'like', "%{$request->search}%");
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->where('is_featured', true)->latest(),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->through(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => $p->price,
            'discount_price' => $p->discount_price,
            'final_price' => $p->final_price,
            'discount_percentage' => $p->discount_percentage,
            'featured_image_url' => $p->featured_image_url,
            'short_description' => $p->short_description,
            'category' => ['name' => $p->category->name, 'slug' => $p->category->slug],
            'stock' => $p->stock,
        ]);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'slug']);

        return Inertia::render('Shop', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['category', 'search', 'sort', 'min_price', 'max_price']),
        ]);
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('short_description', 'like', "%{$q}%");
            })
            ->take(6)
            ->get()
            ->map(fn($p) => [
                'id'                 => $p->id,
                'name'               => $p->name,
                'slug'               => $p->slug,
                'price'              => $p->price,
                'final_price'        => $p->final_price,
                'featured_image_url' => $p->featured_image_url,
                'category'           => $p->category?->name,
            ]);

        return response()->json($products);
    }

    public function show(string $slug): Response
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->price,
                'discount_price' => $p->discount_price,
                'final_price' => $p->final_price,
                'discount_percentage' => $p->discount_percentage,
                'featured_image_url' => $p->featured_image_url,
                'category' => ['name' => $p->category->name, 'slug' => $p->category->slug],
            ]);

        return Inertia::render('ProductDetail', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'final_price' => $product->final_price,
                'discount_percentage' => $product->discount_percentage,
                'featured_image_url' => $product->featured_image_url,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'ingredients' => $product->ingredients,
                'weight' => $product->weight,
                'sku' => $product->sku,
                'stock' => $product->stock,
                'tags' => $product->tags,
                'average_rating' => $product->average_rating,
                'images' => $product->images->map(fn($i) => ['image_url' => $i->image_url]),
                'category' => ['name' => $product->category->name, 'slug' => $product->category->slug],
                'reviews' => $product->reviews->map(fn($r) => [
                    'id' => $r->id,
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'created_at' => $r->created_at->diffForHumans(),
                    'user' => ['name' => $r->user->name, 'avatar' => $r->user->avatar],
                ]),
            ],
            'related' => $related,
        ]);
    }
}
