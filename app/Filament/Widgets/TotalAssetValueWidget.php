<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalAssetValueWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAssets = Asset::count();
        $totalValue = Asset::sum('harga_perolehan');
        $activeAssets = Asset::where('status', 'AKTIF')->count();
        $avgValue = $totalAssets > 0 ? $totalValue / $totalAssets : 0;

        return [
            Stat::make('Total Aset', number_format($totalAssets))
                ->description('Semua aset dalam sistem')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            
            Stat::make('Total Nilai Aset', 'Rp ' . number_format($totalValue, 0, ',', '.'))
                ->description('Total nilai perolehan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            
            Stat::make('Aset Aktif', number_format($activeAssets))
                ->description($totalAssets > 0 ? round(($activeAssets/$totalAssets)*100, 1) . '% dari total' : '0%')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),
            
            Stat::make('Rata-rata Nilai', 'Rp ' . number_format($avgValue, 0, ',', '.'))
                ->description('Per aset')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('primary'),
        ];
    }
}
