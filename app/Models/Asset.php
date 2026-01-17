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
     * 1. Saving: Menarik kode_barang otomatis dari kategori sebelum data disimpan.
     * 2. Updating: Mencatat riwayat mutasi jika terjadi perubahan ruangan (room_id).
     */
    protected static function booted()
    {
        // LOGIC SEBELUM SIMPAN (CREATE/UPDATE)
        static::saving(function ($asset) {
            // Jika user memilih kategori, tarik kode_kategori-nya secara otomatis ke kode_barang
            if ($asset->category_id) {
                $category = \App\Models\Category::find($asset->category_id);
                if ($category) {
                    $asset->kode_barang = $category->kode_kategori;
                }
            }
        });

        // LOGIC SAAT UPDATE RUANGAN (MUTASI)
        static::updating(function ($asset) {
            // Hanya rekam mutasi jika ada perubahan pada room_id
            if ($asset->isDirty('room_id')) {
                $oldRoomId = $asset->getOriginal('room_id');
                $newRoomId = $asset->room_id;

                // Cari nama ruangan asal
                $oldRoomName = \App\Models\Room::find($oldRoomId)?->nama_ruangan ?? 'Lokasi Awal';

                // Cari data ruangan tujuan
                $roomTujuan = \App\Models\Room::find($newRoomId);

                \App\Models\AssetMutation::create([
                    'asset_id' => $asset->id,
                    'ruangan_asal' => $oldRoomName,
                    'ruangan_tujuan' => $roomTujuan->nama_ruangan ?? 'Ruangan Baru',
                    'petugas' => auth()->user()->name ?? 'System',
                    // Mencatat Pj baru dari ruangan tujuan atau dari pemakai eksternal
                    'penanggung_jawab_baru' => $roomTujuan->penanggung_jawab ?? $asset->nama_pemakai ?? '-',
                    'alasan' => 'Perubahan Lokasi Ruangan BMN',
                ]);
            }
        });
    }

    // --- RELASI DATABASE ---

    /**
     * Relasi ke Kategori: Menentukan Kode Akun & Masa Manfaat BMN
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Ruangan: Satu aset berada di satu ruangan internal
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke Peminjaman: Satu aset bisa memiliki banyak riwayat peminjaman
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relasi ke Pemeliharaan: Satu aset bisa memiliki banyak riwayat servis
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
