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
            $table->id();
            $table->string('pemeriksaan');
            $table->string('jenis_pemeriksaan');
            $table->year('tahun_pemeriksaan');
            $table->text('hasil_pemeriksaan');
            $table->string('jenis_temuan');
            $table->text('uraian_temuan');
            $table->text('rekomendasi');
            $table->text('catatan_rekomendasi');
            $table->string('tindak_lanjut');
            $table->string('unit_kerja');
            $table->string('tim_pemantauan');
            $table->date('tenggat_waktu');
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
