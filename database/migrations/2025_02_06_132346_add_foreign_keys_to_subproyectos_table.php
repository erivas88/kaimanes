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
        Schema::table('subproyectos', function (Blueprint $table) {
            $table->foreign(['proyecto_id'], 'Subproyectos_ibfk_1')->references(['proyecto_id'])->on('proyectos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subproyectos', function (Blueprint $table) {
            $table->dropForeign('Subproyectos_ibfk_1');
        });
    }
};
