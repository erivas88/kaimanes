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
        Schema::create('dataloggers', function (Blueprint $table) {
            $table->integer('datalogger_id', true);
            $table->integer('estacion_id')->nullable()->index('estacion_id');
            $table->string('tipo', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('direccion_ftp')->nullable();
            $table->string('usuario_ftp', 100)->nullable();
            $table->string('password_ftp', 100)->nullable();
            $table->integer('frecuencia_envio')->nullable();
            $table->dateTime('fecha_cambio_retiro')->nullable();
            $table->dateTime('fecha_ultima_actualizacion')->nullable();
            $table->string('codigo_barra')->nullable()->unique('codigo_barra');
            $table->string('mac')->nullable();
            $table->integer('sim_id')->nullable()->index('sim_id');
            $table->string('marca', 100)->nullable();
            $table->dateTime('fecha_instalacion')->nullable();
            $table->enum('estado', ['Operativo', 'Reparación', 'Retirado'])->nullable()->default('Operativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataloggers');
    }
};
