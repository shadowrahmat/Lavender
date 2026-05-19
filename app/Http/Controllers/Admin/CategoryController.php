<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return Inertia::render('Admin/Categories/Index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'required|string|max:100|unique:categories,slug',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:10',
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        Category::create($data);
        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'required|string|max:100|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:10',
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $category->update($data);
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a category with products.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
