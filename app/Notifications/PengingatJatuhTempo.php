<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PengingatJatuhTempo extends Notification implements ShouldQueue
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
        $returnDate = Carbon::parse($this->loan->return_date)->format('d F Y');

        return (new MailMessage)
                    ->subject('Pengingat Pengembalian Aset')
                    ->line('Ini adalah pengingat bahwa peminjaman aset Anda akan segera jatuh tempo.')
                    ->line('Mohon untuk segera mengembalikan aset sebelum atau pada tanggal yang ditentukan.')
                    ->line('Aset: ' . $this->loan->asset->name)
                    ->line('Tanggal Pengembalian: ' . $returnDate)
                    ->action('Lihat Detail Peminjaman', url('/admin/loans/' . $this->loan->id))
                    ->line('Terima kasih atas perhatian Anda.');
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
            'message' => 'Pengembalian aset ' . $this->loan->asset->name . ' akan jatuh tempo pada ' . $this->loan->return_date,
        ];
    }
}
