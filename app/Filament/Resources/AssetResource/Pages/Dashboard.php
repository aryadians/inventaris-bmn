<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Mengatur jumlah kolom di halaman Dashboard.
     * Menggunakan 6 kolom pada layar lebar (xl) agar pembagian 3:3 (50%) menjadi pas.
     */
    public function getColumns(): int | string | array
    {
        return 6; // Paksa 6 kolom di semua ukuran layar
    }
}
