<?php

namespace App\Filament\Widgets;

use App\Models\Procurement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ProcurementTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Pengadaan (6 Bulan Terakhir)';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '60s';
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        return Cache::remember('dashboard.procurement_trend', 300, function() {
            $months = [];
            $counts = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');
                $counts[] = Procurement::whereYear('tgl_pengajuan', $date->year)
                    ->whereMonth('tgl_pengajuan', $date->month)
                    ->count();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Pengadaan',
                        'data' => $counts,
                        'backgroundColor' => '#3b82f6',
                        'borderColor' => '#2563eb',
                    ],
                ],
                'labels' => $months,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }
}
