<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'size_ml',
        'price',
        'ingredients_text',
        'is_main',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'size_ml'   => 'integer',
        'price'     => 'decimal:2',
        'is_main'   => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        // image_path is like 'products/filename.jpg'
        return asset('storage/'.$this->image_path);
    }
}