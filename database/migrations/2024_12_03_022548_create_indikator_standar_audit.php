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
        Schema::create('indikator_standar_audit', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_pertanyaan_standar');
            $table->string('pertanyaan_standar');
            $table->string('indikator_pertanyaan');
            $table->string('bukti_objektif');
            $table->string('original_bukti_objektif');
            $table->unsignedBigInteger('id_standar');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->boolean('is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_standar_audit');
    }
};
