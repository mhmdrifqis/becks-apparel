<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;
use App\Helpers\PhoneHelper;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Send the given notification via WhatsApp (Fonnte API).
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return array|null
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return null;
        }

        $message = $notification->toWhatsApp($notifiable);
        if (empty($message)) {
            return null;
        }

        // Resolve target phone number from notifiable or notification object
        $phone = null;
        if (method_exists($notifiable, 'routeNotificationForWhatsapp')) {
            $phone = $notifiable->routeNotificationForWhatsapp($notification);
        } elseif (method_exists($notifiable, 'routeNotificationFor')) {
            $phone = $notifiable->routeNotificationFor('whatsapp', $notification)
                ?? $notifiable->routeNotificationFor('fonnte', $notification);
        }

        if (empty($phone)) {
            $phone = $notifiable->phone
                ?? ($notifiable->recipient_phone ?? null)
                ?? ($notification->order->recipient_phone ?? null)
                ?? ($notification->order->user->phone ?? null);
        }

        $phone = PhoneHelper::normalize($phone);

        if (empty($phone)) {
            Log::warning("WhatsAppChannel: Telepon penerima tidak ditemukan untuk notifikasi " . get_class($notification));
            return null;
        }

        return $this->whatsapp->sendMessage($phone, $message);
    }
}
