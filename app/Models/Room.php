<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // PASTIKAN INI BENAR

class Room extends Model
{
    protected $fillable = [
        'nama_ruangan',
        'penanggung_jawab',
    ];

    /**
     * Relasi ke Model Asset
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
