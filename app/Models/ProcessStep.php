<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'icon_name',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'is_active' => 'boolean',
    ];
}