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
        Schema::create('gateways', function (Blueprint $table) {
            $table->integer('gateway_id', true);
            $table->string('tipo', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->integer('sim_id')->nullable()->index('sim_id');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_instalacion')->nullable();
            $table->enum('estado', ['Operativo', 'Reparación', 'Retirado'])->nullable()->default('Operativo');
            $table->string('eui')->nullable();
            $table->string('region', 100)->nullable();
            $table->string('ip', 100)->nullable();
            $table->text('link_informacion')->nullable();
            $table->string('proveedor', 100)->nullable();
            $table->string('codigo_interno', 100)->nullable();
            $table->string('mac')->nullable();
            $table->string('codigo_barra')->nullable()->unique('codigo_barra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
