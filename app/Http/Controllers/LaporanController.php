<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function cetak()
    {
        // 1. Ambil semua data aset dari database
        $assets = Asset::with('room')->get(); // 'room' untuk ambil nama ruangan

        // 2. Load View (tampilan HTML) dan kirim datanya
        $pdf = Pdf::loadView('laporan.aset', ['assets' => $assets]);

        // 3. Download/Stream PDF
        // 'stream' artinya buka di browser dulu, kalau 'download' langsung unduh file
        return $pdf->stream('Laporan-Aset-BMN.pdf');
    }
}
