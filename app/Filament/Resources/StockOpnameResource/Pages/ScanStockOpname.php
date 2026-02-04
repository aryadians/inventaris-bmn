<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Resources\Pages\Page;

class ScanStockOpname extends Page
{
    protected static string $resource = StockOpnameResource::class;

    public \App\Models\StockOpname $record;
    public ?string $scannedCode = null;

    public function mount(\App\Models\StockOpname $record)
    {
        $this->record = $record;
    }

    public function scan()
    {
        $code = trim($this->scannedCode);
        if (empty($code)) return;

        // Cari Asset berdasarkan Kode Barang atau NUP atau ID (jika QR berisi ID)
        // Format QR kita: KODE-NUP (misal: 1010-001)
        // Atau bisa juga kita cari by ID jika formatnya ID.
        
        $asset = null;
        
        // Coba parsing format KODE-NUP
        if (str_contains($code, '-')) {
            $parts = explode('-', $code);
            $nup = array_pop($parts); // Ambil bagian terakhir sebagai NUP
            $kode = implode('-', $parts); // Sisanya adalah kode barang
            
            $asset = \App\Models\Asset::where('kode_barang', $kode)
                        ->where('nup', (int)$nup)
                        ->first();
        }

        // Jika tidak ketemu, coba cari exact match di kode_barang (siapa tau barcode pabrik)
        if (!$asset) {
            $asset = \App\Models\Asset::where('kode_barang', $code)->first();
        }

        if (!$asset) {
            \Filament\Notifications\Notification::make()
                ->title('Aset tidak ditemukan!')
                ->danger()
                ->send();
            $this->scannedCode = '';
            return;
        }

        // Cek Status Aset
        $status = 'found'; // Default: Tersedia
        $notes = null;

        if ($this->record->room_id && $asset->room_id != $this->record->room_id) {
            $status = 'wrong_room';
            $realRoom = $asset->room->name ?? 'Gudang';
            $notes = "Seharusnya di: {$realRoom}";
            
            \Filament\Notifications\Notification::make()
                ->title('Salah Ruangan!')
                ->body("Aset ini tercatat di {$realRoom}")
                ->warning()
                ->send();
        } else {
             \Filament\Notifications\Notification::make()
                ->title('Aset Terverifikasi')
                ->success()
                ->send();
        }

        // Simpan ke Detail
        \App\Models\StockOpnameDetail::updateOrCreate(
            [
                'stock_opname_id' => $this->record->id,
                'asset_id' => $asset->id,
            ],
            [
                'status' => $status,
                'scanned_at' => now(),
                'notes' => $notes,
            ]
        );

        $this->scannedCode = '';
    }

    public function getRecentScansProperty()
    {
        return $this->record->details()->with('asset')->latest('scanned_at')->take(10)->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => $this->record->details()->count(),
            'found' => $this->record->details()->where('status', 'found')->count(),
            'wrong_room' => $this->record->details()->where('status', 'wrong_room')->count(),
        ];
    }

    public function finish()
    {
        // 1. Ambil semua aset yang SEHARUSNYA ada di ruangan ini
        $expectedAssets = \App\Models\Asset::where('room_id', $this->record->room_id)->pluck('id');

        // 2. Ambil aset yang SUDAH discan
        $scannedAssetIds = $this->record->details()->pluck('asset_id');

        // 3. Cari selisihnya (Missing)
        $missingAssetIds = $expectedAssets->diff($scannedAssetIds);

        // 4. Masukkan ke database sebagai MISSING
        $timestamp = now();
        $data = [];
        foreach ($missingAssetIds as $assetId) {
            $data[] = [
                'stock_opname_id' => $this->record->id,
                'asset_id' => $assetId,
                'status' => 'missing',
                'scanned_at' => $timestamp,
                'notes' => 'Tidak ditemukan saat audit otomatis',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        if (!empty($data)) {
            \App\Models\StockOpnameDetail::insert($data);
        }

        // 5. Update status Stock Opname jadi Completed
        $this->record->update(['status' => 'completed']);
        
        \Filament\Notifications\Notification::make()
            ->title('Audit Selesai')
            ->body(count($data) . ' aset ditandai sebagai Hilang (Missing)')
            ->success()
            ->send();

        return redirect()->to(StockOpnameResource::getUrl());
    }
}
