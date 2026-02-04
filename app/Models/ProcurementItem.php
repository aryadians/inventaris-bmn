<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $fillable = [
        'procurement_id',
        'nama_barang',
        'category_id',
        'jumlah',
        'harga_satuan',
        'spesifikasi',
    ];

    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
