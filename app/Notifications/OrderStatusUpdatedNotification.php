<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/customer/orders/' . $this->order->id);
        
        $message = (new MailMessage)
                    ->subject('Update Status Pesanan #' . $this->order->order_number)
                    ->greeting('Halo ' . $this->order->recipient_name . ',')
                    ->line('Status pesanan Anda #' . $this->order->order_number . ' kini telah diupdate menjadi: **' . $this->newStatusLabel . '**.');

        if ($this->order->status === 'shipped') {
            $message->line('Kurir: ' . ($this->order->courier_name ?? '-'));
            $message->line('Nomor Resi: ' . ($this->order->tracking_number ?? 'Belum ada resi'));
        }

        $message->action('Lacak Pesanan', $url)
                ->line('Terima kasih telah berbelanja!');

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
            'url' => '/customer/orders/' . $this->order->id,
        ];
    }
}
