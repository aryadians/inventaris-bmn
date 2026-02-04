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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->foreignId('pelapor_id')->nullable()->after('asset_id')->constrained('users')->nullOnDelete();
            $table->date('tanggal_lapor')->nullable()->after('pelapor_id');
            $table->string('bukti_foto')->nullable()->after('biaya');
            $table->date('tanggal_selesai')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['pelapor_id']);
            $table->dropColumn(['pelapor_id', 'tanggal_lapor', 'bukti_foto', 'tanggal_selesai']);
        });
    }
};
