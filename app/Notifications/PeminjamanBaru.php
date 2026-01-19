<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PeminjamanBaru extends Notification implements ShouldQueue
{
    use Queueable;

    public $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
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
        return (new MailMessage)
                    ->subject('Permintaan Peminjaman Aset Baru')
                    ->line('Ada permintaan peminjaman aset baru yang perlu ditinjau.')
                    ->line('Aset: ' . $this->loan->asset->name)
                    ->line('Peminjam: ' . $this->loan->user->name)
                    ->line('Tanggal Pinjam: ' . $this->loan->loan_date)
                    ->line('Tanggal Kembali: ' . $this->loan->return_date)
                    ->action('Lihat Detail Peminjaman', url('/admin/loans/' . $this->loan->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'asset_name' => $this->loan->asset->name,
            'user_name' => $this->loan->user->name,
            'message' => 'Permintaan peminjaman baru untuk aset: ' . $this->loan->asset->name,
        ];
    }
}
