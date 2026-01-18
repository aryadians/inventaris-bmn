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
        // Ambil semua aset dengan relasi kategori untuk menghindari N+1
        $assets = Asset::with('category')->get();

        // Hitung total harga perolehan
        $totalHargaPerolehan = $assets->sum('harga_perolehan');

        // Hitung jumlah semua aset
        $totalUnitAset = $assets->count();

        // Hitung aset rusak berat
        $asetRusakBerat = $assets->where('kondisi', 'RUSAK_BERAT')->count();

        // Hitung total nilai buku
        $totalNilaiBuku = $assets->sum(function ($asset) {
            $harga = $asset->harga_perolehan;
            $tanggalPerolehan = \Carbon\Carbon::parse($asset->tanggal_perolehan);
            $masaManfaat = $asset->category->masa_manfaat ?? 1;

            $usiaBarang = $tanggalPerolehan->diffInYears(now());
            $penyusutanPerTahun = $harga / $masaManfaat;

            $totalPenyusutan = $penyusutanPerTahun * $usiaBarang;
            $nilaiBuku = $harga - $totalPenyusutan;

            return $nilaiBuku > 0 ? $nilaiBuku : 0;
        });

        return [
            Stat::make('Total Unit Aset', $totalUnitAset)
                ->description('Semua barang yang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Total Nilai Perolehan', 'Rp ' . number_format($totalHargaPerolehan, 0, ',', '.'))
                ->description('Akumulasi harga perolehan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Aset Rusak Berat', $asetRusakBerat)
                ->description('Perlu penghapusan segera')
                ->descriptionIcon('heroicon-m-trash')
                ->color('danger'),

            Stat::make('Total Nilai Buku', 'Rp ' . number_format($totalNilaiBuku, 0, ',', '.'))
                ->description('Estimasi nilai buku saat ini')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('success'),
        ];
    }
}
