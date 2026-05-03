<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL: Modify the enum column
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE folders MODIFY jenis_dokumen ENUM('RKAKL', 'RAPK', 'SPJ', 'LKJ', 'LAKIP') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL: Revert the enum column
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE folders MODIFY jenis_dokumen ENUM('RKAKL', 'RAPK', 'SPJ', 'Lainnya') NOT NULL");
        }
    }
};
