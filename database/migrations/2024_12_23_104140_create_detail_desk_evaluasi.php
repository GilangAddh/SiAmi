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
        Schema::create('detail_desk_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_desk');
            $table->unsignedBigInteger('id_pernyataan');
            $table->string('pernyataan');
            $table->json('indikator');
            $table->json('pertanyaan');
            $table->json('auditee');
            $table->json('bukti_objektif');
            $table->json('bukti_evaluasi')->nullable();
            $table->string('evaluasi_diri')->nullable();
            $table->json('pertanyaan_tambahan')->nullable();
            $table->string('kategori_temuan')->nullable();
            $table->string('hasil_audit')->nullable();
            $table->json('check_auditor')->nullable();
            $table->datetime('latest_update_time_auditee')->nullable();
            $table->string('latest_update_by_auditee')->nullable();
            $table->datetime('latest_update_time_auditor')->nullable();
            $table->string('latest_update_by_auditor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');

            $table->foreign('id_desk')->references('id')->on('desk_evaluasi')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_pernyataan')->references('id')->on('pernyataan_standar')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_desk_evaluasi');
    }
};
