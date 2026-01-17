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
            if ($asset->isDirty('room_id')) {
                $oldRoomName = \App\Models\Room::find($asset->getOriginal('room_id'))?->nama_ruangan ?? 'Awal';
                $roomTujuan = \App\Models\Room::find($asset->room_id);

                \App\Models\AssetMutation::create([
                    'asset_id' => $asset->id,
                    'ruangan_asal' => $oldRoomName,
                    'ruangan_tujuan' => $roomTujuan->nama_ruangan,
                    'petugas' => auth()->user()->name ?? 'System',
                    'penanggung_jawab_baru' => $roomTujuan->penanggung_jawab ?? $asset->nama_pemakai ?? '-',
                    'alasan' => 'Perubahan Lokasi Ruangan',
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
