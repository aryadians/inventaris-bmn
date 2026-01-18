<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Kode Barang',
            'Nama Barang',
            'NUP',
            'Tanggal Perolehan',
            'Merek',
            'Kondisi',
            'Ruangan',
            'Kategori',
            'Harga Perolehan',
        ];
    }

    /**
     * @param mixed $asset
     *
     * @return array
     */
    public function map($asset): array
    {
        return [
            $asset->id,
            $asset->kode_barang,
            $asset->nama_barang,
            $asset->nup,
            $asset->tanggal_perolehan,
            $asset->merek,
            ucfirst(str_replace('_', ' ', strtolower($asset->kondisi))),
            $asset->room->nama_ruangan ?? 'N/A',
            $asset->category->nama_kategori ?? 'N/A',
            $asset->harga_perolehan,
        ];
    }
}
