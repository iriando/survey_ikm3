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
        Schema::create('pertanyaan_ikms', function (Blueprint $table) {
            $table->id();
            $table->string('kd_unsur')->unique(); // Kode Unsur, nantinya direlasikan dengan Jawaban IKM
            $table->text('pertanyaan'); // Pertanyaan IKM
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_ikms');
    }
};
