<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category',
        'status',
        'additional_price',
        'stock',
        'unit',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
