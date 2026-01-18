<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AssetChart;
use App\Filament\Widgets\AssetConditionWidget;
use App\Filament\Widgets\LatestMutations;
use App\Filament\Widgets\LatestPeminjaman;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | string | array
    {
        return 6;
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            AssetChart::class,
            AssetConditionWidget::class,
            LatestMutations::class,
            LatestPeminjaman::class,
        ];
    }
}
