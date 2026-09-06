<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/pesanan/' . $this->order->order_number);

        return (new MailMessage)
                    ->subject('Pesanan Berhasil Dibuat #' . $this->order->order_number)
                    ->greeting('Halo ' . $this->order->recipient_name . ',')
                    ->line('Terima kasih telah berbelanja di Becks Apparel!')
                    ->line('Pesanan Anda dengan nomor ' . $this->order->order_number . ' telah berhasil dibuat dan menunggu pembayaran.')
                    ->line('Total Tagihan: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
                    ->action('Bayar Sekarang / Lihat Pesanan', $url)
                    ->line('Harap segera melakukan pembayaran agar pesanan dapat segera diproses.');
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
