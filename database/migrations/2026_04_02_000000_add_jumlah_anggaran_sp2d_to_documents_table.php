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
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'jumlah_anggaran_sp2d')) {
                $table->decimal('jumlah_anggaran_sp2d', 18, 2)->nullable()->after('tanggal_sp2d');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'jumlah_anggaran_sp2d')) {
                $table->dropColumn('jumlah_anggaran_sp2d');
            }
        });
    }
};
