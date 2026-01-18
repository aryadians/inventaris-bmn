<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Mengembalikan angka 6 secara absolut untuk mengunci grid
    public function getColumns(): int | string | array
    {
        return 6;
    }
}
