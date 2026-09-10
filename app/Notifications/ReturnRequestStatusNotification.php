<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ReturnRequest;
use App\Channels\WhatsAppChannel;

class ReturnRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $returnRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(ReturnRequest $returnRequest)
    {
        $this->returnRequest = $returnRequest;
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
        $order = $this->returnRequest->order;
        $url = url('/pesanan/' . $order->order_number);
        $recipientName = $order->recipient_name ?? $notifiable->name ?? 'Pelanggan';
        $orderNo = "*#{$order->order_number}*";
        
        $statusLabel = match($this->returnRequest->status) {
            'approved' => 'DISETUJUI ✅',
            'rejected' => 'DITOLAK ❌',
            'completed' => 'RETUR SELESAI 🎉',
            default => strtoupper($this->returnRequest->status)
        };

        $message = "Halo Kak *{$recipientName}*! 👋\n\n"
            . "Status pengajuan retur/pengembalian barang Anda untuk pesanan {$orderNo} kini telah diperbarui menjadi: *{$statusLabel}*.\n";

        if (!empty($this->returnRequest->admin_note)) {
            $message .= "\n📝 *Catatan Admin:* {$this->returnRequest->admin_note}\n";
        }

        $message .= "\nLihat rincian status retur Anda di sini:\n"
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
        $order = $this->returnRequest->order;
        return [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'title' => 'Update Status Retur Barang',
            'message' => 'Pengajuan retur pesanan #' . $order->order_number . ' berstatus: ' . strtoupper($this->returnRequest->status),
            'url' => '/pesanan/' . $order->order_number,
        ];
    }
}
