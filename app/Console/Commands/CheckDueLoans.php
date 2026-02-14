<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Services\WhatsAppService;

class CheckDueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-due-loans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek peminjaman jatuh tempo dan kirim notifikasi WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa peminjaman jatuh tempo...');
        
        $overdueLoans = Loan::where('status', 'DIPINJAM')
            ->whereDate('tanggal_kembali_rencana', '<=', now())
            ->with(['user', 'asset'])
            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman jatuh tempo.');
            return;
        }

        foreach ($overdueLoans as $loan) {
            $user = $loan->user;
            if (!$user || !$user->phone) {
                $this->warn("User {$user->name} tidak memiliki nomor HP.");
                continue;
            }

            $message = "Halo {$user->name},

" .
                       "Pengingat: Peminjaman aset *{$loan->asset->nama_barang}* ({$loan->asset->kode_barang}) " .
                       "telah jatuh tempo pada tanggal {$loan->tanggal_kembali_rencana->format('d M Y')}.
" .
                       "Mohon segera dikembalikan ke bagian sarana.

" .
                       "Terima kasih.";

            $this->info("Mengirim notifikasi ke {$user->name} ({$user->phone})...");
            
            // Send WA (Asynchronous/Queued logic handled by service or simple direct call here)
            // Using service directly for simplicity in command
            $result = WhatsAppService::send($user->phone, $message);
            
            if ($result === true) {
                $this->info("Berhasil dikirim.");
            } else {
                $this->error("Gagal mengirim: " . $result);
            }
        }
        
        $this->info('Selesai.');
    }
}
