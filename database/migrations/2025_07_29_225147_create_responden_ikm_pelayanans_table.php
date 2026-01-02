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
        Schema::create('responden_ikm_pelayanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_biodata');
            // $table->string('kd_unsurikmpembinaan')->nullable();
            $table->string('kd_unsurikmpelayanan')->nullable();
            $table->integer('skor');
            $table->timestamps();

            $table->foreign('id_biodata')->references('id')->on('respondenpelayanans')->onDelete('cascade');

            // $table->foreign('kd_unsurikmpembinaan')->references('kd_unsur')->on('unsurs')->onDelete('cascade')->onUpdate('cascade');

            // $table->foreign('kd_unsurikmpelayanan')->references('kd_unsur')->on('unsurikmpelayanans')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responden_ikms');
    }
};
