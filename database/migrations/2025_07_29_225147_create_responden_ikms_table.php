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
        Schema::create('responden_ikms', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('id_biodata'); // Foreign Key ke tabel responden
            $table->string('kd_unsurikmpembinaan')->nullable();
            $table->string('kd_unsurikmpelayanan')->nullable();
            $table->integer('skor'); // Skor Jawaban
            $table->timestamps();

            // Foreign Key ke tabel responden
            $table->foreign('id_biodata')->references('id')->on('respondens')->onDelete('cascade');

            // Foreign Key ke tabel unsur ikm pembinaan
            $table->foreign('kd_unsurikmpembinaan')->references('kd_unsur')->on('unsurs')->onDelete('cascade');

            // Foreign Key ke tabel unsur ikm pelayanan
            $table->foreign('kd_unsurikmpelayanan')->references('kd_unsur')->on('unsurikmpelayanans')->onDelete('cascade');
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
