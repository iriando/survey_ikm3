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
        Schema::create('jawaban_ikms', function (Blueprint $table) {
            $table->id();
            $table->string('kd_unsur', 25); // Kode Unsur, relasi ke tabel pertanyaan_ikm
            $table->string('isi_jawaban', 255);
            $table->integer('np');
            $table->char('mutu', 1);
            $table->timestamps();

            $table->foreign('kd_unsur')->references('kd_unsur')->on('pertanyaan_ikms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_ikms');
    }
};
