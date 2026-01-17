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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Relasi: Barang apa yang dipinjam?
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();

            // Relasi: Siapa yang meminjam? (User/Petugas)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Data Peminjaman
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana'); // Kapan janji balikin
            $table->date('tanggal_kembali_realisasi')->nullable(); // Kapan beneran balikin (bisa kosong kalau belum balik)

            // Status: Dipinjam / Dikembalikan
            $table->string('status')->default('DIPINJAM'); // Opsinya: DIPINJAM, DIKEMBALIKAN

            $table->text('keterangan')->nullable(); // Misal: "Dipakai untuk sidang TPP"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
