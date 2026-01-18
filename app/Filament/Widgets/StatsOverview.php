<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Hitung total harga semua aset
        $totalAset = Asset::sum('harga_perolehan');

        // Format Rupiah Manual
        $formatRupiah = 'Rp ' . number_format($totalAset, 0, ',', '.');

        return [
            // Kartu 1: Total Unit Aset
            Stat::make('Total Unit Aset', Asset::count())
                ->description('Semua barang yang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            // Kartu 2: Nilai Kekayaan Negara (Total Harga)
            Stat::make('Total Nilai Perolehan', $formatRupiah)
                ->description('Akumulasi harga perolehan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            // Kartu 3: Aset Rusak Berat
            Stat::make('Aset Rusak Berat', Asset::where('kondisi', 'RUSAK_BERAT')->count())
                ->description('Perlu penghapusan segera')
                ->descriptionIcon('heroicon-m-trash')
                ->color('danger'),

            // Kartu 4: Nilai Buku
            Stat::make('Total Nilai Buku', function () {
                $total = Asset::all()->sum(fn($asset) => $asset->nilai_buku);
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
                ->description('Estimasi nilai buku saat ini')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('success'),
        ];
    }
}
