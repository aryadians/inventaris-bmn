<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaintenanceCostOverview extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Hitung biaya bulan ini
        $costThisMonth = \App\Models\Maintenance::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('biaya');

        $activeTickets = \App\Models\Maintenance::whereIn('status', ['pending', 'processing'])->count();
        $completedTickets = \App\Models\Maintenance::where('status', 'completed')->count();

        return [
            Stat::make('Biaya Servis (Bulan Ini)', 'Rp ' . number_format($costThisMonth, 0, ',', '.'))
                ->description('Total pengeluaran perbaikan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
            
            Stat::make('Sedang Diperbaiki', $activeTickets)
                ->description('Tiket status pending/proses')
                ->descriptionIcon('heroicon-m-wrench')
                ->color('warning'),

            Stat::make('Selesai Diperbaiki', $completedTickets)
                ->description('Tiket selesai ditangani')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
