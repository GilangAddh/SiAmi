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
        Schema::create('penugasan_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_auditor');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');

            $table->foreign('id_jadwal')->references('id')->on('jadwal_audit')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_auditor')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_audit');
    }
};
