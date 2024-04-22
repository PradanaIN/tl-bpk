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
        Schema::create('bukti_input_siptl', function (Blueprint $table) {
            $table->id();
            $table->string('bukti_input_siptl')->nullable();
            $table->text('detail_bukti_input_siptl')->nullable();
            $table->string('upload_by')->nullable();
            $table->dateTime('upload_at')->nullable();
            $table->foreignId('rekomendasi_id')->constrained('rekomendasi')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_input_siptl');
    }
};
