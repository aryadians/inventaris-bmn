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
    // ... fungsi cetak() yang lama ...

    // Fungsi Baru: Cetak Bukti Peminjaman per Transaksi
    public function cetakBukti($id)
    {
        // 1. Ambil data peminjaman berdasarkan ID
        $loan = \App\Models\Loan::with(['user', 'asset.room'])->findOrFail($id);

        // 2. Load View khusus surat peminjaman
        $pdf = Pdf::loadView('laporan.bukti_pinjam', ['loan' => $loan]);

        // 3. Stream PDF (Nama file sesuai nama peminjam)
        return $pdf->stream('Bukti-Pinjam-' . $loan->asset->kode_barang . '.pdf');
    }
}
