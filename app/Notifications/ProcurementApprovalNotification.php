<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProcurementApprovalNotification extends Notification
{
    use Queueable;

    protected $procurement;
    protected $action;

    public function __construct($procurement, $action)
    {
        $this->procurement = $procurement;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->action === 'approved' ? 'Disetujui' : 'Ditolak';
        $color = $this->action === 'approved' ? 'success' : 'error';

        return (new MailMessage)
                    ->subject("[SIMA] Pengadaan {$status}")
                    ->greeting("Hello, {$notifiable->name}!")
                    ->line("Pengadaan Anda dengan nomor **{$this->procurement->no_pengajuan}** telah **{$status}**.")
                    ->line("Tanggal Pengajuan: {$this->procurement->tgl_pengajuan->format('d M Y')}")
                    ->line("Total Estimasi: Rp " . number_format($this->procurement->total_estimasi, 0, ',', '.'))
                    ->action('Lihat Detail', url('/admin/procurements/' . $this->procurement->id . '/edit'))
                    ->line('Terima kasih telah menggunakan sistem SIMA!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'procurement_id' => $this->procurement->id,
            'no_pengajuan' => $this->procurement->no_pengajuan,
            'action' => $this->action,
        ];
    }
}
