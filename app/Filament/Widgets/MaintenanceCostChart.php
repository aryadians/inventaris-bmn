<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class MaintenanceCostChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Biaya Pemeliharaan';
    protected static ?int $sort = 5;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        // Ambil data biaya per bulan selama 12 bulan terakhir
        $data = Maintenance::select(
                DB::raw('SUM(biaya) as total'),
                DB::raw("DATE_FORMAT(tanggal_lapor, '%Y-%m') as month")
            )
            ->where('tanggal_lapor', '>=', now()->subMonths(11)->startOfMonth())
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Pastikan semua bulan ada (isi 0 jika kosong)
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $months->put($month, $data->get($month, 0));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Biaya (Rp)',
                    'data' => $months->values()->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
            'labels' => $months->keys()->map(fn($m) => \Carbon\Carbon::parse($m)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
