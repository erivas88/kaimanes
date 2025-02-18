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
        Schema::table('accesos', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'Accesos_ibfk_1')->references(['usuario_id'])->on('usuarios')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['proyecto_id'], 'Accesos_ibfk_2')->references(['proyecto_id'])->on('proyectos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['subproyecto_id'], 'Accesos_ibfk_3')->references(['subproyecto_id'])->on('subproyectos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accesos', function (Blueprint $table) {
            $table->dropForeign('Accesos_ibfk_1');
            $table->dropForeign('Accesos_ibfk_2');
            $table->dropForeign('Accesos_ibfk_3');
        });
    }
};
