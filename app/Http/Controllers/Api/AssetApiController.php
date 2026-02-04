<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetApiController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'room']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kondisi
        if ($request->has('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search by nama_aset
        if ($request->has('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }

        $assets = $query->paginate($request->get('per_page', 15));

        return response()->json($assets);
    }

    /**
     * Display the specified asset.
     */
    public function show($id)
    {
        $asset = Asset::with(['category', 'room', 'loans', 'maintenances'])->findOrFail($id);

        return response()->json($asset);
    }

    /**
     * Get asset by QR code.
     */
    public function getByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $asset = Asset::where('qr_code', $request->qr_code)
            ->with(['category', 'room'])
            ->firstOrFail();

        return response()->json($asset);
    }
}
