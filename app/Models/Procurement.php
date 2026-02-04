<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $fillable = [
        'user_id',
        'no_pengajuan',
        'tgl_pengajuan',
        'status',
        'total_estimasi',
        'notes',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($procurement) {
            if (!$procurement->user_id && auth()->check()) {
                $procurement->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }
}
