<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Asset;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    // Logic NUP Otomatis ada di sini
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Ambil kode barang yang diinput user
        $kodeBarang = $data['kode_barang'];

        // 2. Cari NUP terakhir di database berdasarkan kode barang tersebut
        $lastAsset = Asset::where('kode_barang', $kodeBarang)
            ->orderBy('nup', 'desc')
            ->first();

        // 3. Jika ada barang sebelumnya, NUP + 1. Jika tidak, mulai dari 1.
        $data['nup'] = $lastAsset ? $lastAsset->nup + 1 : 1;

        return $data;
    }
}
