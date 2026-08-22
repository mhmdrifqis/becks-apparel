<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemUpgrade extends Model
{
    use HasFactory;

    protected $table = 'order_item_upgrade';

    protected $fillable = [
        'order_item_id',
        'upgrade_id',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(Upgrade::class);
    }
}
