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
        Schema::table('rab_items', function (Blueprint $table) {
            $table->boolean('surat_tugas')->default(false)->after('kuitansi');
            $table->boolean('laporan')->default(false)->after('surat_tugas');
            $table->boolean('ktp')->default(false)->after('laporan');
            $table->boolean('npwp')->default(false)->after('ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab_items', function (Blueprint $table) {
            $table->dropColumn(['surat_tugas', 'laporan', 'ktp', 'npwp']);
        });
    }
};
