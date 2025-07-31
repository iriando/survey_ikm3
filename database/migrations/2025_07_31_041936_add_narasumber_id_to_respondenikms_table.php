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
            $table->foreignId('narasumber_id')->nullable()->constrained('narasumbers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responden_ikms', function (Blueprint $table) {
            $table->dropForeign(['narasumber_id']);
            $table->dropColumn('narasumber_id');
        });
    }
};
