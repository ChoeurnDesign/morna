<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    protected $fillable = [
        'name',
        'province',
        'location_text',
        'description',
        'map_image_path',
        'main_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}