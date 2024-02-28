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
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('tindak_lanjut');
            $table->string('unit_kerja');
            $table->string('tim_pemantauan');
            $table->date('tenggat_waktu');
            $table->string('dokumen_tindak_lanjut')->nullable();
            $table->string('detail_dokumen_tindak_lanjut')->nullable();
            $table->string('upload_by')->nullable();
            $table->dateTime('upload_at')->nullable();
            $table->string('status_tindak_lanjut')->default('Proses');
            $table->dateTime('status_tindak_lanjut_at')->nullable();
            $table->string('status_tindak_lanjut_by')->nullable();
            $table->string('catatan_tindak_lanjut')->nullable();
            $table->foreignId('rekomendasi_id')->constrained('rekomendasi')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};
