<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('narasumbers', function (Blueprint $table) {
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('narasumbers', function (Blueprint $table) {
            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn('kegiatan_id');
        });
    }
};
