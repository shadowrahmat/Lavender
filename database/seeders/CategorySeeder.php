<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bread & Bakery',
                'slug' => 'bread-bakery',
                'description' => 'Freshly baked breads and classic bakery items made daily.',
                'icon' => '🍞',
                'sort_order' => 1,
            ],
            [
                'name' => 'Cake & Pastry',
                'slug' => 'cake-pastry',
                'description' => 'Beautiful cakes and delicate pastries for every celebration.',
                'icon' => '🎂',
                'sort_order' => 2,
            ],
            [
                'name' => 'Savoury',
                'slug' => 'savoury',
                'description' => 'Delicious savoury snacks, rolls, and rice dishes.',
                'icon' => '🥐',
                'sort_order' => 3,
            ],
            [
                'name' => 'Sweets',
                'slug' => 'sweets',
                'description' => 'Traditional and modern sweets for every sweet tooth.',
                'icon' => '🍮',
                'sort_order' => 4,
            ],
            [
                'name' => 'Ramadan Menu',
                'slug' => 'ramadan-menu',
                'description' => 'Special Ramadan treats and iftar specials.',
                'icon' => '🌙',
                'sort_order' => 5,
            ],
            [
                'name' => 'Corporate Orders',
                'slug' => 'corporate-orders',
                'description' => 'Bulk bakery orders for offices and corporate events.',
                'icon' => '🏢',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
