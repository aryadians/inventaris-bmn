<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\PengingatJatuhTempo;
use Illuminate\Console\Command;
use Carbon\Carbon;

class KirimPengingatJatuhTempo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kirim-pengingat-jatuh-tempo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim notifikasi pengingat H-3 sebelum tanggal pengembalian aset.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mengirim pengingat jatuh tempo...');

        // Tentukan tanggal jatuh tempo, misalnya 3 hari dari sekarang
        $reminderDate = Carbon::now()->addDays(3)->toDateString();

        // Cari semua peminjaman yang statusnya masih 'DIPINJAM' dan akan jatuh tempo dalam 3 hari
        $loansToRemind = Loan::where('status', 'DIPINJAM')
            ->whereDate('tanggal_kembali_rencana', $reminderDate)
            ->get();

        if ($loansToRemind->isEmpty()) {
            $this->info('Tidak ada peminjaman yang jatuh tempo dalam 3 hari. Tidak ada notifikasi yang dikirim.');
            return;
        }

        $this->info("Ditemukan {$loansToRemind->count()} peminjaman yang akan jatuh tempo. Mengirim notifikasi...");

        foreach ($loansToRemind as $loan) {
            // Pastikan relasi user dan asset ada
            if ($loan->user && $loan->asset) {
                // 1. Kirim notifikasi Email/Database (Existing)
                $loan->user->notify(new PengingatJatuhTempo($loan));
                
                // 2. Kirim notifikasi WhatsApp (New)
                if ($loan->user->phone) {
                    $message = "Halo *{$loan->user->name}*,\n\nIni adalah pengingat dari *SIMA Lapas Jombang*. Barang BMN berikut akan jatuh tempo dalam 3 hari:\n\n" .
                               "📦 *Barang:* {$loan->asset->nama_barang}\n" .
                               "🔢 *NUP:* #{$loan->asset->nup}\n" .
                               "📅 *Tenggat:* " . \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->format('d M Y') . "\n\n" .
                               "Mohon segera lakukan pengembalian atau koordinasi lebih lanjut. Terima kasih.";
                    
                    \App\Services\WhatsAppService::send($loan->user->phone, $message);
                }

                $this->line("Notifikasi terkirim ke: {$loan->user->name} untuk aset: {$loan->asset->nama_barang}");
            }
        }

        $this->info('Selesai mengirim semua pengingat jatuh tempo.');
    }
}
