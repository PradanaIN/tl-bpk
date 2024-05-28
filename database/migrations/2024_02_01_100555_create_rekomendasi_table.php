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
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pemeriksaan');
            $table->string('jenis_pemeriksaan');
            $table->year('tahun_pemeriksaan');
            $table->text('hasil_pemeriksaan');
            $table->string('jenis_temuan');
            $table->text('uraian_temuan');
            $table->text('rekomendasi');
            $table->text('catatan_rekomendasi');
            $table->string('lhp');
            $table->string('semester_rekomendasi');
            $table->string('status_rekomendasi')->default('Belum Sesuai');
            $table->string('catatan_pemutakhiran')->nullable();
            $table->string('pemutakhiran_by')->nullable();
            $table->dateTime('pemutakhiran_at')->nullable();
            $table->string('semester_pemutakhiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
