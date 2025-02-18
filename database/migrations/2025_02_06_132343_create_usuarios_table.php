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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('usuario_id', true);
            $table->string('nombre');
            $table->string('email')->nullable()->unique('email');
            $table->string('telefono', 20)->nullable();
            $table->enum('rol', ['Administrador', 'Técnico', 'Cliente'])->nullable()->default('Cliente');
            $table->enum('estado', ['Activo', 'Deshabilitado'])->nullable()->default('Activo');
            $table->string('contraseña');
            $table->dateTime('fecha_creacion')->nullable()->useCurrent();
            $table->dateTime('fecha_modificacion_password')->nullable();
            $table->dateTime('fecha_recuperacion_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
