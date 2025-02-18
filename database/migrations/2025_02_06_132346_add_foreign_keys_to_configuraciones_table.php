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
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->foreign(['sensor_id'], 'Configuraciones_ibfk_1')->references(['sensor_id'])->on('sensores')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tecnico_id'], 'Configuraciones_ibfk_2')->references(['usuario_id'])->on('usuarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->dropForeign('Configuraciones_ibfk_1');
            $table->dropForeign('Configuraciones_ibfk_2');
        });
    }
};
