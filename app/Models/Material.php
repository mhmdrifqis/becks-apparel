<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'image_path',
        'status',
        'additional_price',
        'stock',
        'unit',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) return null;
        return str_starts_with($this->image_path, 'assets/') ? asset($this->image_path) : \Illuminate\Support\Facades\Storage::url($this->image_path);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
