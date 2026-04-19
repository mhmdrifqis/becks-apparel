<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'category',
        'name',
        'slug',
        'base_price',
        'description',
        'specification',
        'features',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];
}
