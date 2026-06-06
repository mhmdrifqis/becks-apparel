<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category',
        'status',
        'additional_price',
        'stock',
        'unit',
        'product_types',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'product_types'    => 'array',
    ];

    /**
     * Filter bahan berdasarkan jenis produk.
     * Bahan dengan product_types = null dianggap berlaku untuk semua produk.
     */
    public function scopeForProductType(Builder $query, string $type): Builder
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
