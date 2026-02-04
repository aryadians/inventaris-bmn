<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'asset_id',
        'pelapor_id',
        'tanggal_lapor',
        'tanggal_servis',
        'masalah',
        'tindakan',
        'vendor',
        'biaya',
        'status',
        'bukti_foto',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_lapor' => 'date',
        'tanggal_servis' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }
}
