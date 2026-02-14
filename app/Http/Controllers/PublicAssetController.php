<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicAssetController extends Controller
{
    public function show($kode, $nup)
    {
        $asset = Asset::where('kode_barang', $kode)
            ->where('nup', $nup)
            ->firstOrFail();

        return view('public.asset_detail', compact('asset'));
    }

    public function reportDamage(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'reporter_name' => 'required|string|max:255',
            'problem' => 'required|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('maintenance-photos', 'public');
        }

        Maintenance::create([
            'asset_id' => $request->asset_id,
            'masalah' => $request->problem . " (Dilaporkan oleh: " . $request->reporter_name . ")",
            'status' => 'pending',
            'tanggal_lapor' => now(),
            'bukti_foto' => $photoPath,
        ]);

        return back()->with('success', 'Laporan kerusakan berhasil dikirim. Tim Sarana akan segera mengecek.');
    }

    public function requestLoan(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'requester_name' => 'required|string|max:255',
            'requester_phone' => 'required|string',
            'duration_days' => 'required|integer|min:1',
        ]);

        // Logic for loan request (could notify admin via WA)
        // Here we just notify via session for now
        
        return back()->with('success', 'Permintaan peminjaman telah dikirim ke Admin BMN.');
    }
}
