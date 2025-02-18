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
        Schema::table('sensores', function (Blueprint $table) {
            $table->foreign(['id_unidad'], 'FK_sensores_unidades')->references(['id_unidad'])->on('unidades')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['estacion_id'], 'Sensores_ibfk_1')->references(['estacion_id'])->on('estaciones')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->dropForeign('FK_sensores_unidades');
            $table->dropForeign('Sensores_ibfk_1');
        });
    }
};
