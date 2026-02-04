<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // 1. Total Unit: Count directly from DB
        $totalUnitAset = Asset::count();

        // 2. Total Harga: Sum directly from DB
        $totalHargaPerolehan = Asset::sum('harga_perolehan');

        // 3. Rusak Berat: Count with condition directly from DB
        $asetRusakBerat = Asset::where('kondisi', 'RUSAK_BERAT')->count();

        // 4. Nilai Buku:
        // Kalkulasi ini berat jika dilakukan di PHP untuk RIBUAN data.
        // Solusi optimal: Gunakan Raw SQL untuk kalkulasi penyusutan di database level.
        // Asumsi: masa_manfaat ada di table categories, join table diperlukan.
        
        $totalNilaiBuku = 0;
        
        // Cek apakah database driver mendukung (MySQL/PostgreSQL aman)
        try {
            // Bergantung pada struktur tabel categories dan assets
            // Kita join ke categories untuk ambil masa_manfaat.
            // Rumus: Nilai Buku = Harga - ( (Harga/MasaManfaat) * (TglSekarang - TglPerolehanFihun) )
            // Karena rumit di SQL diff date, kita ambil simplifikasi atau lakukan chunking jika perlu.
            // Untuk performa maks, kita return 0 atau cached value jika terlalu berat.
            // Disini kita coba pendekatan chunking ringan atau raw SQL sederhana.
            
            // Pendekatan Hybrid: Ambil kolom minimal & hitung di PHP (Memory friendly)
            // Limitasi: Jika data > 10.000, ini pun bisa lambat.
            // Sebaiknya field 'nilai_buku' disimpan fix di database.
            
            Asset::query()
                ->select(['harga_perolehan', 'tanggal_perolehan', 'category_id'])
                ->with('category:id,masa_manfaat') // Eager load minimal
                ->chunk(1000, function ($assets) use (&$totalNilaiBuku) {
                    foreach ($assets as $asset) {
                        $harga = $asset->harga_perolehan;
                        // Default masa manfaat 1 tahun jika null (safety)
                        $masaManfaat = $asset->category->masa_manfaat ?? 1; 
                        
                        // Hitung usia dalam tahun (float)
                        $tgl = \Carbon\Carbon::parse($asset->tanggal_perolehan);
                        $usiaTahun = $tgl->diffInDays(now()) / 365;
                        
                        // Penyusutan
                        $penyusutan = ($harga / $masaManfaat) * $usiaTahun;
                        $nilai = max($harga - $penyusutan, 0);
                        
                        $totalNilaiBuku += $nilai;
                    }
                });
                
        } catch (\Exception $e) {
            $totalNilaiBuku = 0; // Fallback jika error
        }

        return [
            Stat::make('Total Unit Aset', number_format($totalUnitAset, 0, ',', '.'))
                ->description('Semua barang yang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Total Nilai Perolehan', 'Rp ' . number_format($totalHargaPerolehan, 0, ',', '.'))
                ->description('Akumulasi harga perolehan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Aset Rusak Berat', number_format($asetRusakBerat, 0, ',', '.'))
                ->description('Perlu penghapusan segera')
                ->descriptionIcon('heroicon-m-trash')
                ->color('danger'),

            Stat::make('Total Nilai Buku (Est)', 'Rp ' . number_format($totalNilaiBuku, 0, ',', '.'))
                ->description('Estimasi nilai residu saat ini')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),
        ];
    }
}
