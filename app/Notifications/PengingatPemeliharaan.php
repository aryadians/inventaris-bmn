<?php

namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PengingatPemeliharaan extends Notification implements ShouldQueue
{
    use Queueable;

    public $maintenance;

    /**
     * Create a new notification instance.
     */
    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $scheduleDate = Carbon::parse($this->maintenance->tanggal_servis)->format('d F Y');

        return (new MailMessage)
                    ->subject('Pengingat Jadwal Pemeliharaan Aset')
                    ->line('Ini adalah pengingat untuk jadwal pemeliharaan aset yang akan datang.')
                    ->line('Aset: ' . $this->maintenance->asset->name)
                    ->line('Jadwal: ' . $scheduleDate)
                    ->line('Deskripsi Masalah: ' . $this->maintenance->masalah)
                    ->action('Lihat Detail Pemeliharaan', url('/admin/maintenances/' . $this->maintenance->id))
                    ->line('Harap persiapkan segala sesuatu yang diperlukan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'asset_name' => $this->maintenance->asset->name,
            'message' => 'Aset ' . $this->maintenance->asset->name . ' dijadwalkan untuk pemeliharaan pada ' . $this->maintenance->tanggal_servis,
        ];
    }
}
