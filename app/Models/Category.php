<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Mengizinkan pengisian data
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'masa_manfaat',
    ];

    /**
     * Relasi ke Model Asset (Satu kategori memiliki banyak aset)
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
