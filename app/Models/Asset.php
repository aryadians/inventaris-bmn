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
            // Hanya rekam jika room_id berubah
            if ($asset->isDirty('room_id')) {
                $oldRoomId = $asset->getOriginal('room_id');
                $newRoomId = $asset->room_id;

                $oldRoom = \App\Models\Room::find($oldRoomId)?->nama_ruangan ?? 'Awal';
                $roomTujuan = \App\Models\Room::find($newRoomId);

                \App\Models\AssetMutation::create([
                    'asset_id' => $asset->id,
                    'ruangan_asal' => $oldRoom,
                    'ruangan_tujuan' => $roomTujuan->nama_ruangan,
                    'petugas' => auth()->user()->name ?? 'System',
                    'penanggung_jawab_baru' => $roomTujuan->penanggung_jawab ?? '-',
                    'alasan' => 'Perubahan Lokasi Barang',
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
