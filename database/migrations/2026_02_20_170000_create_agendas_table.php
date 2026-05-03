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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status', ['active', 'inactive', 'closed'])->default('active');
            $table->timestamps();
        });

        // Add agenda_id to documents table
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'agenda_id')) {
                $table->unsignedBigInteger('agenda_id')->nullable()->after('user_id');
                $table->foreign('agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'agenda_id')) {
                $table->dropForeign(['agenda_id']);
                $table->dropColumn('agenda_id');
            }
        });

        Schema::dropIfExists('agendas');
    }
};
