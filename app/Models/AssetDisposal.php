<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_penghapusan' => 'date',
    ];

    /**
     * Relasi ke Aset yang dihapus
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Relasi ke User yang menyetujui
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Boot logic: Saat status completed, update status asset jadi DIHAPUS
     */
    protected static function booted()
    {
        static::updated(function ($disposal) {
            if ($disposal->status === 'completed') {
                $disposal->asset->update([
                    'status' => 'DIHAPUS',
                    'kondisi' => 'RUSAK_BERAT', // Asumsi default, atau biarkan kondisi terakhir
                    'ket_lainnya' => 'Dihapus berdasarkan SK: ' . $disposal->no_sk
                ]);
                // Opsional: Soft Delete aset agar hilang dari list aktif
                // $disposal->asset->delete(); 
            }
        });
    }
}
