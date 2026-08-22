<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedCartNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
                    ->subject('Jangan Sampai Kehabisan Slot Produksi, Kak! 🛒')
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Kami perhatikan ada desain jersey keren yang udah nungguin di keranjang kakak nih.')
                    ->line('Yuk segera selesaikan pembayarannya sebelum kehabisan slot produksi dan bahan yang kakak inginkan kehabisan.')
                    ->action('Lanjutkan ke Keranjang', route('customer.cart.index'))
                    ->line('Jika ada pertanyaan atau butuh bantuan, jangan ragu untuk membalas email ini.')
                    ->salutation('Salam hangat, Tim Becks Apparel');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'abandoned_cart',
            'title' => 'Keranjang Belanja',
            'message' => 'Kak, desain jerseynya udah nungguin di keranjang nih! Yuk selesaikan pembayarannya sebelum kehabisan slot produksi.',
            'action_url' => route('customer.cart.index'),
            'icon' => 'shopping-cart',
            'color' => 'brand' // Optional: for styling
        ];
    }
}
