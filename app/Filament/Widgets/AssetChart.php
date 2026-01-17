<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;

class AssetChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kondisi Aset';

    // Angka 2 artinya widget ini akan tampil di urutan kedua (di bawah kartu statistik)
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // 1. Ambil Data Jumlah dari Database
        $baik = Asset::where('kondisi', 'BAIK')->count();
        $rusakRingan = Asset::where('kondisi', 'RUSAK_RINGAN')->count();
        $rusakBerat = Asset::where('kondisi', 'RUSAK_BERAT')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aset',
                    'data' => [$baik, $rusakRingan, $rusakBerat],
                    'backgroundColor' => [
                        '#10b981', // Hijau (Baik)
                        '#f59e0b', // Kuning (Rusak Ringan)
                        '#ef4444', // Merah (Rusak Berat)
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => ['Baik', 'Rusak Ringan', 'Rusak Berat'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Jenis Chart: Donut
    }
}
