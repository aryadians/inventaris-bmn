<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;

class AssetChart extends ChartWidget
{
    // Urutan kedua setelah statistik
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected static ?string $heading = 'Statistik Kondisi Aset';

    protected function getData(): array
    {
        $baik = Asset::where('kondisi', 'BAIK')->count();
        $rusakRingan = Asset::where('kondisi', 'RUSAK_RINGAN')->count();
        $rusakBerat = Asset::where('kondisi', 'RUSAK_BERAT')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aset',
                    'data' => [$baik, $rusakRingan, $rusakBerat],
                    'backgroundColor' => [
                        '#10b981', // Hijau
                        '#f59e0b', // Kuning
                        '#ef4444', // Merah
                    ],
                    'hoverOffset' => 4,
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => ['Baik', 'Rusak Ringan', 'Rusak Berat'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
        ],
    ];
}
