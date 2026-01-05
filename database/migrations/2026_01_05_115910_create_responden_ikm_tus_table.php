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
        Schema::create('responden_ikm_tus', function (Blueprint $table) {
            $table->id();$table->unsignedBigInteger('id_biodata');
            $table->string('kd_unsurikmtu')->nullable();
            $table->decimal('skor', 5, 2);
            $table->timestamps();

            $table->foreign('id_biodata')->references('id')->on('respondentus')->onDelete('cascade');

            $table->foreign('kd_unsurikmtu')->references('kd_unsur')->on('unsurikmtus')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responden_ikm_tus');
    }
};
