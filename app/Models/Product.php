<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description',
        'description', 'ingredients', 'price', 'discount_price', 'weight',
        'stock', 'featured_image', 'is_featured', 'is_active', 'tags',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    protected $appends = ['final_price', 'discount_percentage', 'featured_image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->discount_price) return 0;
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : asset('images/product-placeholder.jpg');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
