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
        Schema::table('respondenpelayanans', function (Blueprint $table) {
            $table->text('kritik_saran')->nullable()->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('respondenpelayanans', function (Blueprint $table) {
            $table->dropColumn('kritik_saran');
        });
    }

};
