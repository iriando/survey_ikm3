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
        Schema::create('pilihan_jawabanikmpelayanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->references('id')->on('pertanyaanikmpelayanans')->onDelete('cascade');
            $table->string('teks_pilihan');
            $table->integer('np');
            $table->char('mutu', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihan_jawabanikmpelayanans');
    }
};
