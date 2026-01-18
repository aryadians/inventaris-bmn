<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;

class AssetChart extends ChartWidget
{
    // Urutan kedua setelah statistik
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 6;

    protected static ?string $maxHeight = '300px';

    protected static ?string $heading = 'Statistik Kondisi Aset';

    protected function getData(): array
    {
        $data = Asset::query()
            ->selectRaw('kondisi, count(*) as total')
            ->groupBy('kondisi')
            ->get()
            ->pluck('total', 'kondisi');

        $baik = $data->get('BAIK', 0);
        $rusakRingan = $data->get('RUSAK_RINGAN', 0);
        $rusakBerat = $data->get('RUSAK_BERAT', 0);

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
