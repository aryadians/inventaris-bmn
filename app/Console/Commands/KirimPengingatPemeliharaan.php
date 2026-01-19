<?php

namespace App\Console\Commands;

use App\Models\Maintenance;
use App\Models\User;
use App\Notifications\PengingatPemeliharaan;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class KirimPengingatPemeliharaan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kirim-pengingat-pemeliharaan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim notifikasi H-1 sebelum jadwal pemeliharaan aset.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mengirim pengingat pemeliharaan...');

        // Tentukan tanggal pemeliharaan, misalnya 1 hari dari sekarang (besok)
        $reminderDate = Carbon::now()->addDay()->toDateString();

        // Cari semua pemeliharaan yang statusnya masih 'PROSES' dan dijadwalkan besok
        $maintenancesToRemind = Maintenance::where('status', 'PROSES')
            ->whereDate('tanggal_servis', $reminderDate)
            ->get();

        if ($maintenancesToRemind->isEmpty()) {
            $this->info('Tidak ada jadwal pemeliharaan untuk besok. Tidak ada notifikasi yang dikirim.');
            return;
        }

        // Ambil semua user dengan role 'Admin'
        $admins = User::role('Admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('Tidak ditemukan user dengan role Admin. Notifikasi tidak dapat dikirim.');
            return;
        }

        $this->info("Ditemukan {$maintenancesToRemind->count()} jadwal pemeliharaan. Mengirim notifikasi ke admin...");

        foreach ($maintenancesToRemind as $maintenance) {
            // Pastikan relasi asset ada
            if ($maintenance->asset) {
                // Kirim notifikasi ke semua admin
                Notification::send($admins, new PengingatPemeliharaan($maintenance));
                $this->line("Notifikasi pemeliharaan untuk aset: {$maintenance->asset->nama_barang} telah dikirim.");
            }
        }

        $this->info('Selesai mengirim semua pengingat pemeliharaan.');
    }
}
