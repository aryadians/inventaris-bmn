<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaintenanceCostWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $thisMonth = Maintenance::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('biaya');
        
        $lastMonth = Maintenance::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('biaya');
        
        $totalPending = Maintenance::where('status', 'pending')->count();
        $totalCompleted = Maintenance::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->count();

        $change = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $change >= 0 ? 'danger' : 'success';

        return [
            Stat::make('Biaya Pemeliharaan Bulan Ini', 'Rp ' . number_format($thisMonth, 0, ',', '.'))
                ->description(($change >= 0 ? '+' : '') . round($change, 1) . '% dari bulan lalu')
                ->descriptionIcon($changeIcon)
                ->color($changeColor),
            
            Stat::make('Pemeliharaan Pending', $totalPending)
                ->description('Menunggu penanganan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Selesai Bulan Ini', $totalCompleted)
                ->description('Maintenance completed')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
