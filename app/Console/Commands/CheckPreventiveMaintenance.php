<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\User;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class CheckPreventiveMaintenance extends Command
{
    protected $signature = 'app:check-preventive-maintenance';
    protected $description = 'Cek aset yang memerlukan pemeliharaan rutin berdasarkan frekuensi servis kategori';

    public function handle()
    {
        $this->info('Memeriksa jadwal pemeliharaan rutin...');

        // Ambil aset yang memiliki frekuensi servis di kategorinya
        $assets = Asset::whereHas('category', function ($query) {
            $query->where('frekuensi_servis', '>', 0);
        })->with(['category', 'maintenances' => function ($query) {
            $query->where('status', 'completed')->latest('tanggal_selesai');
        }])->get();

        foreach ($assets as $asset) {
            $frekuensi = $asset->category->frekuensi_servis;
            
            // Tentukan tanggal acuan terakhir (maintenance terakhir atau tanggal perolehan)
            $lastMaintenance = $asset->maintenances->first();
            $baseDate = $lastMaintenance ? Carbon::parse($lastMaintenance->tanggal_selesai) : Carbon::parse($asset->tanggal_perolehan);
            
            $nextMaintenanceDate = $baseDate->addMonths($frekuensi);
            
            // Jika tanggal servis berikutnya adalah 7 hari lagi
            if ($nextMaintenanceDate->isSameDay(now()->addDays(7))) {
                $this->sendNotification($asset, $nextMaintenanceDate, 7);
            }
            // Jika hari ini adalah hari servis
            elseif ($nextMaintenanceDate->isSameDay(now())) {
                $this->sendNotification($asset, $nextMaintenanceDate, 0);
            }
        }

        $this->info('Pemeriksaan selesai.');
    }

    protected function sendNotification($asset, $date, $daysLeft)
    {
        $admin = User::role('Admin')->first(); // Kirim ke admin pertama atau sesuaikan
        if (!$admin || !$admin->phone) return;

        $prefix = $daysLeft > 0 ? "PENGINGAT (H-{$daysLeft})" : "JADWAL HARI INI";
        
        $message = "📢 *{$prefix}: PEMELIHARAAN RUTIN*

" .
                   "Aset berikut dijadwalkan untuk servis berkala:
" .
                   "📦 *Barang:* {$asset->nama_barang}
" .
                   "🔢 *NUP:* #{$asset->nup}
" .
                   "📅 *Tanggal:* " . $date->format('d M Y') . "
" .
                   "ℹ️ *Rutin:* Setiap {$asset->category->frekuensi_servis} bulan

" .
                   "Mohon segera jadwalkan teknisi/vendor pemeliharaan.";

        WhatsAppService::send($admin->phone, $message);
        $this->info("Notifikasi dikirim untuk {$asset->nama_barang}");
    }
}
