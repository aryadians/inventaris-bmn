<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Room;
use Filament\Widgets\ChartWidget;

class AssetChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Aset per Ruangan';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Mengambil data ruangan beserta jumlah aset di dalamnya
        $data = Room::withCount('assets')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Unit',
                    'data' => $data->pluck('assets_count')->toArray(),
                    // Warna-warni untuk tiap bagian Pie Chart
                    'backgroundColor' => [
                        '#36A2EB',
                        '#FF6384',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
            'labels' => $data->pluck('nama_ruangan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Atau 'doughnut'
    }
}
