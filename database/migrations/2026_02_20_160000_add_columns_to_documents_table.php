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
            // Add user_id if it doesn't exist
            if (!Schema::hasColumn('documents', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            // Add jenis_dokumen if it doesn't exist
            if (!Schema::hasColumn('documents', 'jenis_dokumen')) {
                $table->string('jenis_dokumen')->default('Lainnya')->after('nama_dokumen');
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('documents', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('ukuran_file');
            }
            
            // Add keterangan if it doesn't exist
            if (!Schema::hasColumn('documents', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('status');
            }
            
            // Add tahun if it doesn't exist
            if (!Schema::hasColumn('documents', 'tahun')) {
                $table->year('tahun')->nullable()->after('keterangan');
            }
            
            // Add admin_id if it doesn't exist
            if (!Schema::hasColumn('documents', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('tahun');
                $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('documents', 'admin_id')) {
                $table->dropForeign(['admin_id']);
            }
            if (Schema::hasColumn('documents', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            
            // Drop columns
            $table->dropColumn([
                'user_id',
                'jenis_dokumen',
                'status',
                'keterangan',
                'tahun',
                'admin_id'
            ]);
        });
    }
};
