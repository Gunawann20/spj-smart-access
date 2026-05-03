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
        // Modify documents table to be standalone (not dependent on folders)
        Schema::table('documents', function (Blueprint $table) {
            $table->string('jenis_dokumen')->default('Lainnya')->after('nama_dokumen');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('ukuran_file');
            $table->text('keterangan')->nullable()->after('status');
            $table->year('tahun')->nullable()->after('keterangan');
            $table->unsignedBigInteger('admin_id')->nullable()->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['jenis_dokumen', 'status', 'keterangan', 'tahun', 'admin_id']);
        });
    }
};
