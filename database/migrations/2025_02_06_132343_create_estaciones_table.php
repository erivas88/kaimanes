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
        Schema::create('estaciones', function (Blueprint $table) {
            $table->integer('estacion_id', true);
            $table->integer('subproyecto_id')->nullable()->index('subproyecto_id');
            $table->string('nombre');
            $table->string('map_name');
            $table->text('descripcion')->nullable();
            $table->integer('sector')->nullable()->index('fk_estaciones_sectores');
            $table->text('ubicacion_geografica')->nullable();
            $table->string('configurada_por')->nullable();
            $table->dateTime('fecha_configuracion')->nullable();
            $table->enum('estado', ['Operativa', 'Mantenimiento', 'Fuera de servicio'])->nullable()->default('Operativa');
            $table->string('nombre_autoridad')->nullable();
            $table->string('codigo_operacion', 100)->nullable();
            $table->string('codigo_bna', 100)->nullable();
            $table->string('cuenca')->nullable();
            $table->string('subcuenca')->nullable();
            $table->string('region', 100)->nullable();
            $table->float('utm_north')->nullable();
            $table->float('utm_east')->nullable();
            $table->string('utm_datum', 50)->nullable();
            $table->string('utm_zone', 50)->nullable();
            $table->float('latitud_decimal')->nullable();
            $table->float('longitud_decimal')->nullable();
            $table->float('cota')->nullable();
            $table->text('acceso')->nullable();
            $table->enum('estado_reporte_sma', ['Enviado', 'Pendiente', 'Fallido'])->nullable();
            $table->string('tipo_monitoreo', 100)->nullable();
            $table->string('link_imagen', 100)->nullable();
            $table->string('icon_title', 100)->nullable();
            $table->integer('zona_id')->nullable()->index('zona_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estaciones');
    }
};
