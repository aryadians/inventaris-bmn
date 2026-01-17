<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMutation extends Model
{
    protected $fillable = [
        'asset_id',
        'ruangan_asal',
        'ruangan_tujuan',
        'petugas',
        'alasan'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
