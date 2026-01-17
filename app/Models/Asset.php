<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use SoftDeletes; // Mengaktifkan fitur hapus sementara (sampah)

    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = [];

    /**
     * Logic Otomatis (Booted):
     * Setiap kali kolom 'room_id' berubah, sistem akan mencatat riwayat mutasi
     * ke tabel asset_mutations secara otomatis.
     */
    protected static function booted()
    {
        static::updating(function ($asset) {
            // Cek apakah ada perubahan pada ruangan (lokasi)
            if ($asset->isDirty('room_id')) {
                $oldRoomId = $asset->getOriginal('room_id');
                $newRoomId = $asset->room_id;

                // Ambil nama ruangan untuk catatan sejarah
                $oldRoomName = \App\Models\Room::find($oldRoomId)?->nama_ruangan ?? 'Lokasi Awal';
                $newRoomName = \App\Models\Room::find($newRoomId)?->nama_ruangan ?? 'Lokasi Baru';

                // Simpan ke tabel riwayat mutasi
                \App\Models\AssetMutation::create([
                    'asset_id' => $asset->id,
                    'ruangan_asal' => $oldRoomName,
                    'ruangan_tujuan' => $newRoomName,
                    'petugas' => auth()->user()->name ?? 'System',
                    'alasan' => 'Perubahan data lokasi oleh Admin',
                ]);
            }
        });
    }

    // --- RELASI DATABASE ---

    /**
     * Relasi ke Ruangan: Satu aset berada di satu ruangan
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke Peminjaman: Satu aset bisa dipinjam berkali-kali
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relasi ke Pemeliharaan: Satu aset bisa diservis berkali-kali
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Relasi ke Mutasi: Melihat riwayat perpindahan ruangan aset ini
     */
    public function mutations(): HasMany
    {
        return $this->hasMany(AssetMutation::class);
    }
}
