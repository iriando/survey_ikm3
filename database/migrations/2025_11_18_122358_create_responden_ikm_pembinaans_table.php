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
        Schema::create('responden_ikm_pembinaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_biodata');
            $table->string('kd_unsurikmpembinaan')->nullable();
            $table->decimal('skor', 5, 2);
            $table->timestamps();

            $table->foreignId('narasumber_id')->nullable()->constrained('narasumbers')->onDelete('set null');

            $table->foreign('id_biodata')->references('id')->on('respondenpembinaans')->onDelete('cascade');

            $table->foreign('kd_unsurikmpembinaan')->references('kd_unsur')->on('unsurikmpembinaans')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responden_ikm_pembinaans');
    }
};
