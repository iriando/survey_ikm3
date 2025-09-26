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
        Schema::table('responden_ikms', function (Blueprint $table) {
            $table->string('kd_unsurikmtu')->nullable()->after('kd_unsurikmpelayanan');

            $table->foreign('kd_unsurikmtu')->references('kd_unsur')->on('unsurikmtus')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responden_ikms', function (Blueprint $table) {
            //
        });
    }
};
