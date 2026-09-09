<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Helpers\PhoneHelper;
use Illuminate\Support\Facades\Log;

class OrderStatusObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Hanya kirim jika status berubah
        if (!$order->isDirty('status')) {
            return;
        }

        $statusLabels = [
            'paid'      => 'Antrean Masuk (Pembayaran Diterima)',
            'printing'  => 'Proses Cetak Sublim',
            'sewing'    => 'Proses Jahit Jersey',
            'qc'        => 'Quality Control & Finishing',
            'ready'     => 'Selesai Produksi (Siap Kirim)',
            'shipped'   => 'Pesanan Dikirim',
            'completed' => 'Pesanan Selesai',
            'cancelled' => 'Pesanan Dibatalkan',
        ];

        $label = $statusLabels[$order->status] ?? $order->status;

        $user = $order->user;
        if ($user) {
            $user->notify(new OrderStatusUpdatedNotification($order, $label));
        } else {
            // Jika pesanan guest tanpa user_id, kirim notifikasi langsung via route WhatsApp
            $phone = PhoneHelper::normalize($order->recipient_phone);
            if (!empty($phone)) {
                \Illuminate\Support\Facades\Notification::route('whatsapp', $phone)
                    ->notify(new OrderStatusUpdatedNotification($order, $label));
            } else {
                Log::info("Notifikasi WA dilewati untuk Order #{$order->order_number}: Tidak ada nomor telepon penerima.");
            }
        }
    }
}
