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
        Schema::create('respondenpelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('usia');
            $table->string('gender', 255);
            $table->string('nohp', 255);
            $table->string('pendidikan', 255);
            $table->string('pekerjaan', 255);
            $table->string('instansi', 255)->nullable();
            $table->string('j_layanan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondens');
    }
};
