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
    // Cetak Usulan Penghapusan (Menerima banyak ID)
    public function cetakUsulan(Request $request)
    {
        // Ambil ID dari URL (format: ?ids=1,2,3)
        $ids = explode(',', $request->query('ids'));

        // Ambil data aset berdasarkan ID tersebut
        $assets = Asset::whereIn('id', $ids)->get();

        $pdf = Pdf::loadView('laporan.usulan_penghapusan', ['assets' => $assets]);
        return $pdf->stream('Usulan-Penghapusan-BMN.pdf');
    }
    public function cetakLabel($id)
    {
        $asset = \App\Models\Asset::findOrFail($id);

        // Ukuran kertas custom untuk printer barcode (misal: 5cm x 3cm)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.label_qr', ['asset' => $asset])
            ->setPaper([0, 0, 150, 100]); // Ukuran poin untuk stiker kecil

        return $pdf->stream('label-qr-' . $asset->nup . '.pdf');
    }
    public function cetakPenyusutan()
    {
        // Mengambil semua aset yang memiliki kategori (untuk masa manfaat)
        $assets = \App\Models\Asset::with('category', 'room')->get();

        $data = [
            'assets' => $assets,
            'date' => now()->format('d F Y'),
            'total_nilai_perolehan' => $assets->sum('harga_perolehan'),
            'total_nilai_buku' => $assets->sum('nilai_buku'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.penyusutan_report', $data)
            ->setPaper('a4', 'landscape'); // Landscape agar kolom muat banyak

        return $pdf->stream('Laporan-Penyusutan-BMN.pdf');
    }
}
