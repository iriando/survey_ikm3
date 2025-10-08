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
        Schema::table('pilihan_jawabanikmpelayanans', function (Blueprint $table) {
            $table->decimal('bobot', 5, 2)->default(0)->after('np');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilihan_jawabanikmpelayanans', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
    }
};
