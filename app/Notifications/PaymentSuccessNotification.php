<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Channels\WhatsAppChannel;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WhatsAppChannel::class];
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $url = url('/pesanan/' . $this->order->order_number);
        $recipientName = $this->order->recipient_name ?? $notifiable->name ?? 'Pelanggan';
        $orderNo = "*#{$this->order->order_number}*";

        return "Halo Kak *{$recipientName}*! 🎉\n\n"
            . "Kami telah menerima pembayaran untuk pesanan Anda {$orderNo}.\n\n"
            . "Pesanan Anda kini resmi masuk dalam antrean produksi tim Becks Apparel! 🧵✨\n\n"
            . "Pantau progres pesanan Anda kapan saja di sini:\n"
            . "🔗 {$url}\n\n"
            . "Terima kasih atas kepercayaannya!";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'title' => 'Pembayaran Berhasil',
            'message' => 'Pembayaran pesanan #' . $this->order->order_number . ' telah diterima. Masuk antrean produksi.',
            'url' => '/pesanan/' . $this->order->order_number,
        ];
    }
}
