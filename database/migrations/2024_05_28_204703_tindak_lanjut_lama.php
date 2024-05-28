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
        Schema::create('tindak_lanjut_old', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('tindak_lanjut');
            $table->string('unit_kerja');
            $table->string('tim_pemantauan');
            $table->date('tenggat_waktu');
            $table->string('semester_tindak_lanjut');
            $table->string('bukti_tindak_lanjut')->nullable();
            $table->text('detail_bukti_tindak_lanjut')->nullable();
            $table->string('upload_by')->nullable();
            $table->dateTime('upload_at')->nullable();
            $table->string('status_tindak_lanjut')->default('Belum Sesuai');
            $table->dateTime('status_tindak_lanjut_at')->nullable();
            $table->string('status_tindak_lanjut_by')->nullable();
            $table->string('catatan_tindak_lanjut')->nullable();
            $table->foreignUuid('rekomendasi_id')->constrained('rekomendasi')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut_old');
    }
};
