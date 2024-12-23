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
        Schema::create('pernyataan_standar', function (Blueprint $table) {
            $table->id();
            // $table->string('nomer_pertanyaan_standar');
            $table->string('pernyataan_standar');
            $table->json('indikator_pertanyaan')->nullable();
            $table->json('pertanyaan')->nullable();
            $table->json('bukti_objektif')->nullable();
            $table->json('auditee')->nullable();
            $table->unsignedBigInteger('id_standar');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->boolean('is_active')->default(false);

            $table->foreign('id_standar')->references('id')->on('standar_audit')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_standar');
    }
};
