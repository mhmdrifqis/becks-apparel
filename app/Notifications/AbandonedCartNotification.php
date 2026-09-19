<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;

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
        return ['database', WhatsAppChannel::class];
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $url = route('customer.cart.index');
        $name = $notifiable->name ?? 'Kak';

        return "Halo Kak *{$name}*! 🛒🔥\n\n"
            . "Kami perhatikan ada desain jersey keren yang sedang menunggu di keranjang Anda nih!\n\n"
            . "Yuk segera selesaikan pemesanan sebelum kehabisan slot produksi dan ketersediaan bahan yang Anda pilih.\n\n"
            . "Klik link berikut untuk membuka keranjang Anda:\n"
            . "🔗 {$url}\n\n"
            . "Salam hangat,\n*Tim Becks Apparel*";
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
            'color' => 'brand'
        ];
    }
}
