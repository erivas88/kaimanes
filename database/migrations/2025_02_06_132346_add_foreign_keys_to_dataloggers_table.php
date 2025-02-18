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
        Schema::table('dataloggers', function (Blueprint $table) {
            $table->foreign(['estacion_id'], 'Dataloggers_ibfk_1')->references(['estacion_id'])->on('estaciones')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['sim_id'], 'Dataloggers_ibfk_2')->references(['sim_id'])->on('planessim')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dataloggers', function (Blueprint $table) {
            $table->dropForeign('Dataloggers_ibfk_1');
            $table->dropForeign('Dataloggers_ibfk_2');
        });
    }
};
