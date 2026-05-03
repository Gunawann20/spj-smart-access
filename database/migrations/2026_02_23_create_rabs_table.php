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
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agenda_id')->nullable()->constrained('agendas')->onDelete('set null');
            $table->string('judul_rab');
            $table->string('nomor_rab')->unique();
            $table->date('tanggal_rab');
            $table->year('tahun_anggaran');
            $table->text('keterangan_rab')->nullable();
            $table->decimal('total_jumlah', 15, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });

        Schema::create('rab_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rabs')->onDelete('cascade');
            $table->text('uraian');
            $table->decimal('volume', 10, 2);
            $table->string('satuan');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_items');
        Schema::dropIfExists('rabs');
    }
};
