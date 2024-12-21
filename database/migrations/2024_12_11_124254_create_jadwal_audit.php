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
        Schema::create('jadwal_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periode');
            $table->unsignedBigInteger('id_unit');
            $table->unsignedBigInteger('id_standar');
            $table->unsignedBigInteger('id_pernyataan');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');

            $table->foreign('id_periode')->references('id')->on('periode_audit')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_unit')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_standar')->references('id')->on('standar_audit')
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
        Schema::dropIfExists('jadwal_audit');
    }
};
