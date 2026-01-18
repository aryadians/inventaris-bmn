<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssetApiController extends Controller
{
    /**
     * Find an asset by its kode_barang and nup.
     *
     * @param string $kode_barang
     * @param string $nup
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByCode(string $kode_barang, string $nup): JsonResponse
    {
        $asset = Asset::where('kode_barang', $kode_barang)
                      ->where('nup', $nup)
                      ->first();

        if (!$asset) {
            return response()->json(['error' => 'Aset tidak ditemukan'], 404);
        }

        return response()->json(['id' => $asset->id]);
    }
}
