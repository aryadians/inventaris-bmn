<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman depan langsung ke Dashboard Admin
Route::redirect('/', '/admin');

// Grouping Route untuk Laporan agar lebih rapi
Route::middleware(['auth'])->group(function () {
    Route::get('/cetak-laporan', [LaporanController::class, 'cetak'])->name('cetak_laporan');
    Route::get('/cetak-bukti/{id}', [LaporanController::class, 'cetakBukti'])->name('cetak_bukti');
    Route::get('/cetak-usulan', [LaporanController::class, 'cetakUsulan'])->name('cetak_usulan');
    // Tambahkan di dalam group middleware auth
    Route::get('/cetak-label/{id}', [LaporanController::class, 'cetakLabel'])->name('cetak_label');
    Route::get('/cetak-sptjm/{id}', [LaporanController::class, 'cetakSptjm'])->name('cetak_sptjm');
});
