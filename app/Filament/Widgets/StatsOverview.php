<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
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
            Stat::make('Total Nilai Aset', $formatRupiah)
                ->description('Akumulasi harga perolehan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'), // Warna Hijau

            // Kartu 3: Aset Rusak Berat (Warning)
            Stat::make('Aset Rusak Berat', Asset::where('kondisi', 'RUSAK_BERAT')->count())
                ->description('Perlu penghapusan segera')
                ->descriptionIcon('heroicon-m-trash')
                ->color('danger'), // Warna Merah
        ];
    }
}
