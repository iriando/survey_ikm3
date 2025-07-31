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
        Schema::create('nilai_persepsi_ikms', function (Blueprint $table) {
            $table->id();
            $table->integer('np');
            $table->float('ni_terendah');
            $table->float('ni_tertinggi');
            $table->float('nik_terendah');
            $table->float('nik_tertinggi');
            $table->string('mutu_pelayanan', 255);
            $table->string('kinerja', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_persepsi_ikms');
    }
};
