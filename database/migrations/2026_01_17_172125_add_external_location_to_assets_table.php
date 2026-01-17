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
        Schema::table('assets', function (Blueprint $table) {
            $table->boolean('is_external')->default(false); // Status: Di Kantor atau Luar
            $table->string('alamat_eksternal')->nullable(); // Alamat Rumah Dinas/Pihak Ketiga
            $table->string('nip_pemakai')->nullable();      // NIP Pegawai yang membawa
            $table->string('nama_pemakai')->nullable();     // Nama Pegawai yang membawa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};
