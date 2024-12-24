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
        Schema::create('desk_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->date('hard_periode_awal');
            $table->date('hard_periode_akhir');
            $table->string('hard_unit');
            $table->string('hard_standar');
            $table->json('hard_auditor');
            $table->unsignedBigInteger('soft_periode');
            $table->unsignedBigInteger('soft_unit');
            $table->unsignedBigInteger('soft_standar');
            $table->json('soft_auditor');
            $table->string('status')->default("Menunggu Pengisian Evaluasi");
            $table->date('tanggal_mulai_evaluasi')->nullable();
            $table->date('tanggal_mulai_audit')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desk_evaluasi');
    }
};
