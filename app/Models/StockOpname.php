<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'tanggal',
        'room_id',
        'assigned_user_id',
        'status',
        'note',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
}
