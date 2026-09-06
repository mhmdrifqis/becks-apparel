<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/pesanan/' . $this->order->order_number);

        return (new MailMessage)
                    ->subject('Pembayaran Berhasil Diterima #' . $this->order->order_number)
                    ->greeting('Halo ' . $this->order->recipient_name . ',')
                    ->line('Kami telah menerima pembayaran untuk pesanan Anda #' . $this->order->order_number . '.')
                    ->line('Pesanan Anda akan segera diproses ke tahap produksi.')
                    ->action('Pantau Pesanan Anda', $url)
                    ->line('Terima kasih atas kepercayaannya!');
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
