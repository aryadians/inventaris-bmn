<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProcurementTrendChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Tren Pengadaan Barang (Biaya Per Tahun)';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil data biaya perolehan 10 tahun terakhir
        $data = Asset::query()
            ->selectRaw('YEAR(tanggal_perolehan) as year, SUM(harga_perolehan) as total')
            ->whereNotNull('tanggal_perolehan')
            ->where('tanggal_perolehan', '>=', now()->subYears(10)->startOfYear())
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Biaya Pengadaan (Rp)',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#3b82f6', // Biru
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
