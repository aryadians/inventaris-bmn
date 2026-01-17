<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Room; // Import Model Ruangan
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar baca header baris 1

class AssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Cari ID Ruangan berdasarkan Nama (Kalau gak ada, buat baru)
        // Pastikan di Excel nama kolomnya 'lokasi_ruangan'
        $room = Room::firstOrCreate(
            ['nama_ruangan' => $row['lokasi_ruangan']],
            ['nama_ruangan' => $row['lokasi_ruangan']] // Default jika buat baru
        );

        // 2. Simpan Data Aset
        return new Asset([
            'room_id'           => $room->id,
            'kode_barang'       => $row['kode_barang'],
            'nama_barang'       => $row['nama_barang'],
            'nup'               => $row['nup'] ?? rand(1, 999), // Isi random jika kosong
            'kondisi'           => strtoupper($row['kondisi']), // Pastikan Huruf Besar (BAIK/RUSAK)
            'tanggal_perolehan' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_perolehan']),
            'harga_perolehan'   => $row['harga_perolehan'],
            'foto'              => null, // Foto dikosongi dulu
        ]);
    }
}