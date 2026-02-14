<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menjadwalkan command untuk berjalan setiap hari
Schedule::command('app:kirim-pengingat-jatuh-tempo')->daily();
Schedule::command('app:check-due-loans')->daily(); // Cek overdue setiap hari
Schedule::command('app:kirim-pengingat-pemeliharaan')->daily();

// Backup Database & Files
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');
