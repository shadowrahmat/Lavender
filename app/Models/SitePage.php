<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = ['slug', 'title', 'last_updated', 'sections'];

    protected function casts(): array
    {
        return ['sections' => 'array'];
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
