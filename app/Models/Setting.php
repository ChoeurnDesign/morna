<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'contact_email',
        'contact_phone',
        'address',
        'footer_text',
    ];

    /**
     * Get the application settings (singleton pattern)
     */
    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'site_name' => 'Morna Mulberry',
            'footer_text' => '© ' . date('Y') . ' Morna Mulberry. All rights reserved.',
        ]);
    }

    /**
     * Update settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::getSettings();
        $settings->update($data);
        return $settings;
    }

    /**
     * Get site name with fallback
     */
    public function getSiteNameAttribute($value)
    {
        return $value ?: 'Morna Mulberry';
    }
}