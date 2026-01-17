<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Tambahkan bagian ini (Daftar kolom yang boleh diisi)
    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'penanggung_jawab',
    ];
}
