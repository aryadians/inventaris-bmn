<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $guarded = [];

    // Relasi ke User (Peminjam)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Asset (Barang)
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
