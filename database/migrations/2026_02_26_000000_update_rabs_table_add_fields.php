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
        // Update rabs table
        Schema::table('rabs', function (Blueprint $table) {
            if (!Schema::hasColumn('rabs', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable()->after('tanggal_rab');
            }
            if (!Schema::hasColumn('rabs', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
            }
            if (!Schema::hasColumn('rabs', 'tempat_pelaksanaan')) {
                $table->string('tempat_pelaksanaan')->nullable()->after('waktu_selesai');
            }
            if (!Schema::hasColumn('rabs', 'sumber_kegiatan')) {
                $table->text('sumber_kegiatan')->nullable()->after('tempat_pelaksanaan');
            }
            if (!Schema::hasColumn('rabs', 'jenis_kegiatan')) {
                $table->enum('jenis_kegiatan', ['rapat', 'online', 'workshop', 'pelatihan', 'lainnya'])->default('rapat')->after('sumber_kegiatan');
            }
            if (!Schema::hasColumn('rabs', 'akun_yang_digunakan')) {
                $table->string('akun_yang_digunakan')->nullable()->after('jenis_kegiatan');
            }
            if (!Schema::hasColumn('rabs', 'nama_pemoton')) {
                $table->string('nama_pemoton')->nullable()->after('akun_yang_digunakan');
            }
            if (!Schema::hasColumn('rabs', 'nama_direktur')) {
                $table->string('nama_direktur')->nullable()->after('nama_pemoton');
            }
            if (!Schema::hasColumn('rabs', 'nama_pejabat')) {
                $table->string('nama_pejabat')->nullable()->after('nama_direktur');
            }
        });

        // Update rab_items table
        Schema::table('rab_items', function (Blueprint $table) {
            if (!Schema::hasColumn('rab_items', 'potongan_50_persen')) {
                $table->decimal('potongan_50_persen', 15, 2)->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('rab_items', 'pajak')) {
                $table->decimal('pajak', 15, 2)->default(0)->after('potongan_50_persen');
            }
            if (!Schema::hasColumn('rab_items', 'jumlah_pasca_pajak')) {
                $table->decimal('jumlah_pasca_pajak', 15, 2)->default(0)->after('pajak');
            }
            if (!Schema::hasColumn('rab_items', 'surat_undangan')) {
                $table->boolean('surat_undangan')->default(false)->after('jumlah_pasca_pajak');
            }
            if (!Schema::hasColumn('rab_items', 'kak')) {
                $table->boolean('kak')->default(false)->after('surat_undangan');
            }
            if (!Schema::hasColumn('rab_items', 'materi')) {
                $table->boolean('materi')->default(false)->after('kak');
            }
            if (!Schema::hasColumn('rab_items', 'notulen')) {
                $table->boolean('notulen')->default(false)->after('materi');
            }
            if (!Schema::hasColumn('rab_items', 'absen')) {
                $table->boolean('absen')->default(false)->after('notulen');
            }
            if (!Schema::hasColumn('rab_items', 'kuitansi')) {
                $table->boolean('kuitansi')->default(false)->after('absen');
            }
            if (!Schema::hasColumn('rab_items', 'keterangan_item')) {
                $table->text('keterangan_item')->nullable()->after('kuitansi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab_items', function (Blueprint $table) {
            $columns = ['potongan_50_persen', 'pajak', 'jumlah_pasca_pajak', 'surat_undangan', 'kak', 'materi', 'notulen', 'absen', 'kuitansi', 'keterangan_item'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('rab_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('rabs', function (Blueprint $table) {
            $columns = ['waktu_mulai', 'waktu_selesai', 'tempat_pelaksanaan', 'sumber_kegiatan', 'jenis_kegiatan', 'akun_yang_digunakan', 'nama_pemoton', 'nama_direktur', 'nama_pejabat'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('rabs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
