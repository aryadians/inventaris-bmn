<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static bool $isLazy = true;
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

        // 4. Nilai Buku: Cached for 1 hour to improve performance
        $totalNilaiBuku = \Illuminate\Support\Facades\Cache::remember('total_nilai_buku', 3600, function () {
            $total = 0;
            try {
                Asset::query()
                    ->select(['harga_perolehan', 'tanggal_perolehan', 'category_id'])
                    ->with('category:id,masa_manfaat')
                    ->chunk(1000, function ($assets) use (&$total) {
                        foreach ($assets as $asset) {
                            $harga = $asset->harga_perolehan;
                            $masaManfaat = $asset->category->masa_manfaat ?? 1; 
                            
                            $tgl = \Carbon\Carbon::parse($asset->tanggal_perolehan);
                            $usiaTahun = $tgl->diffInDays(now()) / 365;
                            
                            $penyusutan = ($harga / $masaManfaat) * $usiaTahun;
                            $nilai = max($harga - $penyusutan, 0);
                            
                            $total += $nilai;
                        }
                    });
            } catch (\Exception $e) {
                return 0;
            }
            return $total;
        });

        return [
            Stat::make('Total Unit Aset', number_format($totalUnitAset, 0, ',', '.'))
                ->description('Aset Terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Total Nilai Perolehan', 'Rp ' . number_format($totalHargaPerolehan, 0, ',', '.'))
                ->description('Kapitalisasi Aset')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([15, 4, 10, 2, 12, 4, 11])
                ->color('success'),

            Stat::make('Aset Rusak Berat', number_format($asetRusakBerat, 0, ',', '.'))
                ->description('Butuh Tindakan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([2, 10, 5, 12, 4, 11, 4])
                ->color('danger'),

            Stat::make('Total Nilai Buku (Est)', 'Rp ' . number_format($totalNilaiBuku, 0, ',', '.'))
                ->description('Nilai Sisa')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->chart([7, 12, 10, 15, 10, 12, 17])
                ->color('info'),
        ];
    }
}
