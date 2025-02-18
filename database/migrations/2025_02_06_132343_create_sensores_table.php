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
        Schema::create('sensores', function (Blueprint $table) {
            $table->integer('sensor_id', true);
            $table->integer('estacion_id')->nullable()->index('estacion_id');
            $table->string('tipo', 100);
            $table->integer('id_unidad')->default(0)->index('fk_sensores_unidades');
            $table->text('descripcion')->nullable();
            $table->json('configuracion')->nullable();
            $table->dateTime('fecha_instalacion')->nullable();
            $table->enum('estado', ['Operativo', 'Reparación', 'Retirado'])->nullable()->default('Operativo');
            $table->integer('frecuencia_transmision')->nullable();
            $table->string('tecnologia', 100)->nullable();
            $table->text('link_informacion')->nullable();
            $table->dateTime('fecha_actualizacion_configuracion')->nullable();
            $table->dateTime('fecha_cambio_baja')->nullable();
            $table->string('numero_serial')->nullable()->unique('numero_serial');
            $table->string('device_eui')->nullable();
            $table->string('region_transmision', 100)->nullable();
            $table->string('version_firmware', 100)->nullable();
            $table->string('clase', 50)->nullable();
            $table->string('activation', 50)->nullable();
            $table->string('application_eui')->nullable();
            $table->string('device_addr')->nullable();
            $table->string('application_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};
