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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Relasi: Barang apa yang diservis?
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();

            $table->date('tanggal_servis');
            $table->string('masalah'); // Misal: "LCD Mati", "Freon Habis"
            $table->string('tindakan'); // Misal: "Ganti LCD", "Isi Freon"
            $table->string('vendor')->nullable(); // Nama Toko/Bengkel
            $table->decimal('biaya', 15, 2)->default(0); // Biaya perbaikan

            // Status Servis
            $table->string('status')->default('PROSES'); // PROSES / SELESAI

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
