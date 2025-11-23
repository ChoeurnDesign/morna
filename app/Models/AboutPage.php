<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $table = 'about_page';

    protected $fillable = [
        'title',
        'subtitle',
        'story_text',
        'mission_text',
        'vision_text',
        'banner_image_path',
    ];

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image_path ? asset('storage/' . $this->banner_image_path) : null;
    }

    /**
     * Get the about page content (singleton)
     */
    public static function getContent()
    {
        return static::first();
    }
}