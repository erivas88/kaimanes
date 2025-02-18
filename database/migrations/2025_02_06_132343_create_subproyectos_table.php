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
        Schema::create('subproyectos', function (Blueprint $table) {
            $table->integer('subproyecto_id', true);
            $table->integer('proyecto_id')->index('proyecto_id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subproyectos');
    }
};
