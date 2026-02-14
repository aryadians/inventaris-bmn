<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;

class BackupWhatsAppNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $message;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['database']; // Keep in database, and we'll call WA service manually or via a custom channel
    }

    public function toArray($notifiable): array
    {
        // Side effect: Send WhatsApp when notification is created/sent
        if ($this->status === 'success') {
            $waMessage = "✅ *BACKUP BERHASIL*

Sistem SIMA Lapas Jombang telah berhasil melakukan backup database dan file secara otomatis.

📅 Waktu: " . now()->format('d M Y H:i');
        } else {
            $waMessage = "❌ *BACKUP GAGAL*

Terjadi kesalahan saat melakukan backup sistem. Mohon segera periksa log server.

⚠️ Error: " . $this->message;
        }

        if ($notifiable->phone) {
            WhatsAppService::send($notifiable->phone, $waMessage);
        }

        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
