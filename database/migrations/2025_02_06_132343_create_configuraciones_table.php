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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->integer('configuracion_id', true);
            $table->integer('sensor_id')->nullable()->index('sensor_id');
            $table->integer('tecnico_id')->nullable()->index('tecnico_id');
            $table->json('configuracion')->nullable();
            $table->dateTime('fecha_configuracion')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
