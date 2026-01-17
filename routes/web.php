<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk mencetak laporan
Route::get('/cetak-laporan', [LaporanController::class, 'cetak'])->name('cetak_laporan');
Route::get('/cetak-bukti/{id}', [LaporanController::class, 'cetakBukti'])->name('cetak_bukti');
