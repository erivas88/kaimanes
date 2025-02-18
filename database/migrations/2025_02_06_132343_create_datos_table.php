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
        Schema::create('datos', function (Blueprint $table) {
            $table->integer('dato_id', true);
            $table->integer('sensor_id')->nullable();
            $table->dateTime('fecha_hora');
            $table->string('tipo_variable', 100)->nullable();
            $table->float('valor')->nullable();
            $table->string('unidad', 50)->nullable();
            $table->text('trama_original')->nullable();
            $table->boolean('decodificado')->nullable()->default(false);

            $table->unique(['sensor_id', 'fecha_hora', 'tipo_variable', 'valor'], 'unique_data_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos');
    }
};
