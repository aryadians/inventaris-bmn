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
        Schema::create('asset_disposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->date('tanggal_penghapusan');
            $table->string('no_sk')->nullable()->comment('Nomor Surat Keputusan Penghapusan');
            $table->string('jenis_penghapusan')->default('Pemusnahan'); // Pemusnahan, Lelang, Hibah
            $table->decimal('nilai_buku_saat_ini', 15, 2)->default(0);
            $table->decimal('harga_limit', 15, 2)->nullable()->comment('Jika dilelang');
            $table->decimal('harga_terbentuk', 15, 2)->nullable()->comment('Jika dilelang');
            $table->text('keterangan')->nullable();
            $table->string('file_sk')->nullable(); // Upload PDF SK
            $table->string('file_ba')->nullable(); // Upload Berita Acara
            $table->string('status')->default('draft'); // draft, approved, completed
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_disposals');
    }
};
