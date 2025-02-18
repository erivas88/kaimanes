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
        Schema::create('clientes', function (Blueprint $table) {
            $table->string('rut', 20)->nullable()->unique('rut');
            $table->integer('cliente_id', true);
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email')->nullable()->unique('email');
            $table->string('comuna', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('pais', 100)->nullable();
            $table->string('calle')->nullable();
            $table->string('numero', 50)->nullable();
            $table->dateTime('fecha_creacion')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
