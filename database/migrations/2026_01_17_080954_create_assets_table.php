<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            // Ini relasi ke tabel rooms. Jika ruangan dihapus, barang tidak ikut terhapus (cascade option bisa diatur)
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();

            $table->string('kode_barang'); // Contoh: 3.05.01.04.002
            $table->string('nama_barang'); // Contoh: Laptop ASUS
            $table->integer('nup');        // NUP: 1, 2, 3...
            $table->string('kondisi');     // Baik, Rusak Ringan, Rusak Berat
            $table->date('tanggal_perolehan');
            $table->decimal('harga_perolehan', 15, 2); // Pakai decimal untuk uang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
