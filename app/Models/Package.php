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
        'images',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
