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
        // Assets table indexes
        Schema::table('assets', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('room_id');
            $table->index('kondisi');
            $table->index('status');
            $table->index('tanggal_perolehan');
        });

        // Loans table indexes
        Schema::table('loans', function (Blueprint $table) {
            $table->index('asset_id');
            $table->index('status');
            $table->index('tanggal_pinjam');
        });

        // Maintenances table indexes
        Schema::table('maintenances', function (Blueprint $table) {
            $table->index('asset_id');
            $table->index('pelapor_id');
            $table->index('status');
            $table->index('tanggal_servis');
        });

        // Procurements table indexes
        Schema::table('procurements', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index('tgl_pengajuan');
        });

        // Stock opnames table indexes
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->index('room_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['room_id']);
            $table->dropIndex(['kondisi']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_perolehan']);
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['asset_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_pinjam']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropIndex(['asset_id']);
            $table->dropIndex(['pelapor_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_servis']);
        });

        Schema::table('procurements', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tgl_pengajuan']);
        });

        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropIndex(['room_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });
    }
};
