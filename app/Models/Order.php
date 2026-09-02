<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'notes',
        'status',
        'shipping_cost',
        'shipping_service',
        'courier_name',
        'tracking_number',
        'total_amount',
        'deposit_amount',
        'payment_status',
        'payment_token',
        'payment_gateway_id',
        'snap_url',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequest(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public function statusLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status')) {
                $statusLabels = [
                    'paid'      => 'Antrean Masuk',
                    'printing'  => 'Proses Cetak',
                    'sewing'    => 'Proses Jahit',
                    'qc'        => 'Quality Control',
                    'ready'     => 'Selesai Produksi (Siap Kirim)',
                    'shipped'   => 'Pesanan Dikirim',
                    'completed' => 'Pesanan Selesai',
                    'cancelled' => 'Pesanan Dibatalkan',
                ];

                $label = $statusLabels[$order->status] ?? $order->status;

                $order->statusLogs()->create([
                    'status' => $order->status,
                    'description' => $label
                ]);

                // Send notification
                if ($order->user && $order->status !== 'paid' && $order->status !== 'pending' && $order->status !== 'unpaid') {
                    $order->user->notify(new \App\Notifications\OrderStatusUpdatedNotification($order, $label));
                }
            }
        });
    }
}
