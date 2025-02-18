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
        Schema::table('alarmas', function (Blueprint $table) {
            $table->foreign(['sensor_id'], 'Alarmas_ibfk_1')->references(['sensor_id'])->on('sensores')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alarmas', function (Blueprint $table) {
            $table->dropForeign('Alarmas_ibfk_1');
        });
    }
};
