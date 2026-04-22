<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class OrderStatusObserver
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Hanya kirim jika status berubah
        if (!$order->isDirty('status')) {
            return;
        }

        $user = $order->user;
        if (!$user || empty($user->phone)) {
            Log::info("Notifikasi WA dilewati untuk Order #{$order->order_number}: User tidak memiliki nomor telepon.");
            return;
        }

        $status = $order->status;
        $message = $this->getMessageTemplate($status, $order);

        if ($message) {
            $this->whatsapp->sendMessage($user->phone, $message);
        }
    }

    /**
     * Template pesan berdasarkan status
     */
    protected function getMessageTemplate(string $status, Order $order): ?string
    {
        $orderInfo = "*#{$order->order_number}*";
        $appName = "Becks Apparel";

        return match ($status) {
            'paid' => "Halo Kak! Pembayaran untuk pesanan {$orderInfo} telah kami terima. ✨\n\nSekarang pesanan Anda sudah masuk dalam antrean produksi. Kami akan kabari lagi jika sudah masuk tahap pengerjaan ya. Terima kasih!",
            
            'printing', 'sewing' => "Update Pesanan {$orderInfo}! 🧵\n\nSaat ini pesanan sedang dalam *Tahap Produksi*. Tim kami sedang mengerjakan desain terbaik untuk Anda. Mohon ditunggu ya Kak!",
            
            'ready' => "Kabar Gembira! 📦\n\nPesanan {$orderInfo} Anda sudah *Selesai Produksi* dan sedang dalam proses packing untuk dikirim. Siap-siap kedatangan paket keren ya!",
            
            'shipped' => "Pesanan {$orderInfo} Telah Dikirim! 🚀\n\nKurir: *{$order->courier_name}*\nNo. Resi: *{$order->tracking_number}*\n\nAnda bisa melacak kiriman tersebut melalui website ekspedisi terkait. Terima kasih telah berbelanja di {$appName}!",
            
            'cancelled' => "Informasi Pesanan {$orderInfo} ⚠️\n\nMohon maaf, pesanan Anda telah *Dibatalkan*. Jika Anda merasa ini adalah kesalahan atau butuh bantuan lebih lanjut, silakan hubungi admin kami. Terima kasih.",
            
            default => null,
        };
    }
}
