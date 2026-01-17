<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Room;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // 1. Logika Mencari Ruangan berdasarkan Nama (Case Insensitive)
        $room = Room::where('nama_ruangan', 'like', '%' . $row['ruangan'] . '%')->first();

        // 2. Logika Mencari Kategori berdasarkan Nama
        $category = Category::where('nama_kategori', 'like', '%' . $row['kategori'] . '%')->first();

        return new Asset([
            'nama_barang'       => $row['nama_barang'],
            'kode_barang'       => $category?->kode_kategori ?? $row['kode_barang'], // Ambil dari kategori atau manual
            'nup'               => $row['nup'],
            'room_id'           => $room?->id ?? 1, // Jika ruangan tidak ketemu, masukkan ke ID 1 (Gudang/Default)
            'category_id'       => $category?->id,
            'kondisi'           => strtoupper(str_replace(' ', '_', $row['kondisi'])), // "Baik" jadi "BAIK"
            'tanggal_perolehan' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_perolehan']),
            'harga_perolehan'   => $row['harga_perolehan'],
            'is_external'       => false,
        ]);
    }

    // Validasi agar data Excel yang masuk tidak berantakan
    public function rules(): array
    {
        return [
            'nama_barang'     => 'required|string',
            'nup'             => 'required',
            'ruangan'         => 'required', // Nama Ruangan di Excel
            'kategori'        => 'required', // Nama Kategori di Excel
            'harga_perolehan' => 'required|numeric',
            'tanggal_perolehan' => 'required',
        ];
    }
}
