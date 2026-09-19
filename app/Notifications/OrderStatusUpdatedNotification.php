<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Channels\WhatsAppChannel;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $newStatusLabel;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $newStatusLabel)
    {
        $this->order = $order;
        $this->newStatusLabel = $newStatusLabel;
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

        $message = "Halo Kak *{$recipientName}*! 👋\n\n"
            . "Status pesanan Anda {$orderNo} kini telah diperbarui menjadi: *{$this->newStatusLabel}*.\n";

        if ($this->order->status === 'shipped') {
            $courier = $this->order->courier_name ?? '-';
            $tracking = $this->order->tracking_number ?? 'Belum ada resi';
            $message .= "\n🚚 *Kurir:* {$courier}\n📦 *No. Resi:* {$tracking}\n";
        }

        $message .= "\nLacak detail pesanan Anda di sini:\n"
            . "🔗 {$url}\n\n"
            . "Terima kasih telah berbelanja di *Becks Apparel*! ⚽🔥";

        return $message;
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
            'title' => 'Status Pesanan Update',
            'message' => 'Pesanan #' . $this->order->order_number . ' kini berstatus: ' . $this->newStatusLabel,
            'url' => '/pesanan/' . $this->order->order_number,
        ];
    }
}
