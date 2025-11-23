<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlideImage extends Model
{
    protected $fillable = [
        'hero_slide_id',
        'image_path',
        'sort_order',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return $this->image_path
            ? Storage::url($this->image_path)
            : null;
    }

    public function slide()
    {
        return $this->belongsTo(HeroSlide::class);
    }
}