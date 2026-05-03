<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'pelaksana')) {
                $table->string('pelaksana')->nullable()->after('nama_dokumen');
            }

            if (!Schema::hasColumn('documents', 'kode_ro')) {
                $table->string('kode_ro')->nullable()->after('pelaksana');
            }

            if (!Schema::hasColumn('documents', 'jumlah_anggaran')) {
                $table->decimal('jumlah_anggaran', 18, 2)->nullable()->after('kode_ro');
            }
        });

        // Migrate legacy values so existing data is still usable.
        if (Schema::hasColumn('documents', 'jenis_dokumen') && Schema::hasColumn('documents', 'pelaksana')) {
            DB::statement("UPDATE documents SET pelaksana = jenis_dokumen WHERE pelaksana IS NULL AND jenis_dokumen IS NOT NULL");
        }

        if (Schema::hasColumn('documents', 'tahun') && Schema::hasColumn('documents', 'kode_ro')) {
            DB::statement("UPDATE documents SET kode_ro = CAST(tahun AS CHAR) WHERE kode_ro IS NULL AND tahun IS NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('documents', 'jumlah_anggaran')) {
                $dropColumns[] = 'jumlah_anggaran';
            }

            if (Schema::hasColumn('documents', 'kode_ro')) {
                $dropColumns[] = 'kode_ro';
            }

            if (Schema::hasColumn('documents', 'pelaksana')) {
                $dropColumns[] = 'pelaksana';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
