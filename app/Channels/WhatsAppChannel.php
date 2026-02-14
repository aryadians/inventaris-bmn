<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;

class WhatsAppChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $phone = $notifiable->phone_number ?? $notifiable->phone; // Adjust based on your User model

        if ($phone && $message) {
            WhatsAppService::send($phone, $message);
        }
    }
}
