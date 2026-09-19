<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Channels\WhatsAppChannel;

class OrderCreatedNotification extends Notification implements ShouldQueue
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
        $totalAmount = 'Rp ' . number_format($this->order->total_amount, 0, ',', '.');

        return "Halo Kak *{$recipientName}*! 👋\n\n"
            . "Terima kasih telah berbelanja di *Becks Apparel*! 👕✨\n\n"
            . "Pesanan Anda dengan nomor *#{$this->order->order_number}* telah berhasil dibuat dan menunggu pembayaran.\n\n"
            . "💰 *Total Tagihan:* {$totalAmount}\n\n"
            . "Silakan lakukan pembayaran dan cek rincian pesanan Anda melalui link berikut:\n"
            . "🔗 {$url}\n\n"
            . "Harap segera melakukan pembayaran agar pesanan dapat segera diproses ke tahap produksi. Terima kasih!";
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
            'title' => 'Pesanan Dibuat',
            'message' => 'Pesanan #' . $this->order->order_number . ' berhasil dibuat. Segera lakukan pembayaran.',
            'url' => '/pesanan/' . $this->order->order_number,
        ];
    }
}
