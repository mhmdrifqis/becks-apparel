<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category',
        'allowed_categories',
        'description',
        'image_path',
        'status',
        'additional_price',
        'stock',
        'unit',
        'product_types',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'product_types'    => 'array',
        'allowed_categories' => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) return null;
        return str_starts_with($this->image_path, 'assets/') ? asset($this->image_path) : \Illuminate\Support\Facades\Storage::url($this->image_path);
    }

    /**
     * Filter bahan berdasarkan jenis produk.
     * Bahan dengan product_types = null dianggap berlaku untuk semua produk.
     */
    public function scopeForProductType($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->whereNull('product_types')
              ->orWhereJsonContains('product_types', strtolower($type));
        });
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
