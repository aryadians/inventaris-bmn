<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan aset yang bisa difilter.
     */
    public function laporanAset(Request $request)
    {
        $query = Asset::with(['category', 'room']);

        // Terapkan filter berdasarkan input request
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_perolehan', [$request->start_date, $request->end_date]);
        }

        $assets = $query->paginate(15)->withQueryString(); // withQueryString agar filter tetap ada di link pagination

        // Data untuk mengisi dropdown filter
        $categories = Category::all();
        $rooms = Room::all();
        $conditions = ['BAIK' => 'Baik', 'RUSAK_RINGAN' => 'Rusak Ringan', 'RUSAK_BERAT' => 'Rusak Berat'];

        return view('laporan.aset', [
            'assets' => $assets,
            'categories' => $categories,
            'rooms' => $rooms,
            'conditions' => $conditions,
            'filters' => $request->all() // Kirim filter aktif kembali ke view
        ]);
    }

    /**
     * Mencetak laporan aset (PDF) dengan filter yang diterapkan.
     */
    public function cetakAset(Request $request)
    {
        $query = Asset::with(['category', 'room']);

        // Terapkan filter (logika yang sama persis dengan di atas)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_perolehan', [$request->start_date, $request->end_date]);
        }
        
        $assets = $query->get();
        
        $data = [
            'records' => $assets, 
            'date' => now()->format('d F Y'),
            'filters' => $request->all()
        ];
        
        // Menggunakan view pdf.assets_report yang sudah ada
        $pdf = Pdf::loadView('pdf.assets_report', $data)->setPaper('a4', 'landscape');
        
        return $pdf->stream('Laporan-Aset-BMN-Difilter.pdf');
    }

    // Fungsi Cetak Bukti Peminjaman per Transaksi
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
        $assets = \App\Models\Asset::with('category', 'room')
                                    ->whereNotNull('harga_perolehan')
                                    ->where('harga_perolehan', '>', 0)
                                    ->whereNotNull('tanggal_perolehan')
                                    ->get();

        // Lakukan perhitungan penyusutan di sini (dalam PHP)
        $processedAssets = $assets->map(function ($asset) {
            $hargaPerolehan = $asset->harga_perolehan;
            $tanggalPerolehan = \Carbon\Carbon::parse($asset->tanggal_perolehan);
            // Default masa manfaat 1 tahun jika tidak ada, untuk hindari division by zero
            $masaManfaat = $asset->category->masa_manfaat ?? 1;
            if ($masaManfaat == 0) $masaManfaat = 1;


            $usiaTahun = $tanggalPerolehan->diffInYears(now());
            // Batasi usia barang sesuai masa manfaatnya
            if ($usiaTahun > $masaManfaat) {
                $usiaTahun = $masaManfaat;
            }

            $penyusutanPerTahun = $hargaPerolehan / $masaManfaat;
            $akumulasiPenyusutan = $penyusutanPerTahun * $usiaTahun;
            $nilaiBuku = $hargaPerolehan - $akumulasiPenyusutan;

            // Pastikan nilai buku tidak negatif, standar akuntansi nilai sisa adalah Rp 1 atau 0
            $nilaiBuku = $nilaiBuku > 0 ? $nilaiBuku : 0;

            // Tambahkan hasil kalkulasi ke objek aset
            $asset->akumulasi_penyusutan = $akumulasiPenyusutan;
            $asset->nilai_buku_dihitung = $nilaiBuku; // Gunakan nama baru agar tidak konflik dengan accessor
            return $asset;
        });

        // Hitung total dari data yang sudah diproses
        $totalNilaiPerolehan = $processedAssets->sum('harga_perolehan');
        $totalAkumulasiPenyusutan = $processedAssets->sum('akumulasi_penyusutan');
        $totalNilaiBuku = $processedAssets->sum('nilai_buku_dihitung');

        $data = [
            'assets' => $processedAssets,
            'date' => now()->format('d F Y'),
            'total_nilai_perolehan' => $totalNilaiPerolehan,
            'total_akumulasi_penyusutan' => $totalAkumulasiPenyusutan,
            'total_nilai_buku' => $totalNilaiBuku,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.penyusutan_report', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Penyusutan-BMN.pdf');
    }
}

