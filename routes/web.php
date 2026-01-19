<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AssetApiController;
use App\Http\Controllers\ScanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman depan langsung ke Dashboard Admin
Route::redirect('/', '/admin');

// Grouping Route untuk Laporan agar lebih rapi
Route::middleware(['auth'])->group(function () {
        // Route untuk halaman laporan aset (HTML interaktif dengan filter)
    Route::get('/laporan/aset', [LaporanController::class, 'laporanAset'])->name('laporan.aset');
    // Route untuk mencetak laporan aset ke PDF (dengan filter)
    Route::get('/laporan/aset/pdf', [LaporanController::class, 'cetakAset'])->name('laporan.aset.pdf');
    Route::get('/cetak-bukti/{id}', [LaporanController::class, 'cetakBukti'])->name('cetak_bukti');
    Route::get('/cetak-usulan', [LaporanController::class, 'cetakUsulan'])->name('cetak_usulan');
    // Tambahkan di dalam group middleware auth
    Route::get('/cetak-label/{id}', [LaporanController::class, 'cetakLabel'])->name('cetak_label');
    Route::get('/cetak-sptjm/{id}', [LaporanController::class, 'cetakSptjm'])->name('cetak_sptjm');
    Route::get('/cetak-penyusutan', [LaporanController::class, 'cetakPenyusutan'])->name('cetak_penyusutan');
    Route::get('/api/asset/find/{kode_barang}/{nup}', [AssetApiController::class, 'findByCode'])->name('api.asset.find');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
});
