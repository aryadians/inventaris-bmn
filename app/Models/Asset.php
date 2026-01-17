<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    // Biar gak error "MassAssignmentException" lagi
    protected $guarded = [];

    // Relasi: "Satu Aset itu MILIK satu Ruangan"
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    // Tambahkan ini di dalam class Asset
    public function loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
