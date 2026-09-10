<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'proof_images',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'proof_images' => 'array',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($returnRequest) {
            if ($returnRequest->order) {
                $returnRequest->order->statusLogs()->create([
                    'status' => $returnRequest->order->status,
                    'description' => 'Pengajuan retur/pengembalian barang dikirim oleh pelanggan.'
                ]);
            }
        });

        static::updated(function ($returnRequest) {
            if ($returnRequest->isDirty('status')) {
                $labels = [
                    'pending' => 'Pengajuan Retur Pending Review',
                    'approved' => 'Pengajuan Retur DISETUJUI oleh Admin',
                    'rejected' => 'Pengajuan Retur DITOLAK oleh Admin',
                    'completed' => 'Proses Retur Barang SELESAI',
                ];

                $desc = $labels[$returnRequest->status] ?? ('Status retur: ' . $returnRequest->status);
                if ($returnRequest->admin_note) {
                    $desc .= ' (Catatan Admin: ' . $returnRequest->admin_note . ')';
                }

                if ($returnRequest->order) {
                    $returnRequest->order->statusLogs()->create([
                        'status' => $returnRequest->order->status,
                        'description' => $desc
                    ]);
                }

                // Kirim Notifikasi ke User (In-App & WhatsApp)
                if ($returnRequest->user) {
                    try {
                        $returnRequest->user->notify(new \App\Notifications\ReturnRequestStatusNotification($returnRequest));
                    } catch (\Exception $e) {
                        // Safe fallback jika notifikasi mengalami kendala
                    }
                }
            }
        });
    }
}
