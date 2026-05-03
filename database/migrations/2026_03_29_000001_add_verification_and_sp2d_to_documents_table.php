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
            if (!Schema::hasColumn('documents', 'nama_verifikator')) {
                $table->string('nama_verifikator')->nullable()->after('jumlah_anggaran');
            }

            if (!Schema::hasColumn('documents', 'tanggal_sp2d')) {
                $table->date('tanggal_sp2d')->nullable()->after('nama_verifikator');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('documents', 'tanggal_sp2d')) {
                $dropColumns[] = 'tanggal_sp2d';
            }

            if (Schema::hasColumn('documents', 'nama_verifikator')) {
                $dropColumns[] = 'nama_verifikator';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
