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
        Schema::create('zonas', function (Blueprint $table) {
            $table->integer('zona_id', true);
            $table->string('nombre_zona');
            $table->string('nombre_autoridad')->nullable();
            $table->string('codigo_operacional', 100)->nullable();
            $table->text('ubicacion_geografica')->nullable();
            $table->json('poligono_geografico')->nullable();
            $table->float('utm_north')->nullable();
            $table->float('utm_east')->nullable();
            $table->string('utm_datum', 50)->nullable();
            $table->string('utm_zone', 50)->nullable();
            $table->float('latitud_decimal')->nullable();
            $table->float('longitud_decimal')->nullable();
            $table->json('imagenes_referenciales')->nullable();
            $table->dateTime('fecha_creacion')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
