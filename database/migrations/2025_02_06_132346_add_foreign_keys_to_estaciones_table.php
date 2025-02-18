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
        Schema::table('estaciones', function (Blueprint $table) {
            $table->foreign(['subproyecto_id'], 'Estaciones_ibfk_1')->references(['subproyecto_id'])->on('subproyectos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['zona_id'], 'Estaciones_ibfk_2')->references(['zona_id'])->on('zonas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['sector'], 'FK_estaciones_sectores')->references(['id_sector'])->on('sectores')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estaciones', function (Blueprint $table) {
            $table->dropForeign('Estaciones_ibfk_1');
            $table->dropForeign('Estaciones_ibfk_2');
            $table->dropForeign('FK_estaciones_sectores');
        });
    }
};
