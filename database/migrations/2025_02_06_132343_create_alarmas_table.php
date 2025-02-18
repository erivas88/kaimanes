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
        Schema::create('alarmas', function (Blueprint $table) {
            $table->integer('alarma_id', true);
            $table->integer('sensor_id')->nullable()->index('sensor_id');
            $table->string('tipo_alarma', 100)->nullable();
            $table->enum('categoria', ['Crítica', 'Operativa']);
            $table->string('tipo_variable_gatillo', 100)->nullable();
            $table->float('umbral_min')->nullable();
            $table->float('umbral_max')->nullable();
            $table->enum('notificar_por', ['Email', 'SMS', 'WhatsApp'])->nullable();
            $table->integer('repeticiones')->nullable()->default(1);
            $table->text('mensaje')->nullable();
            $table->dateTime('fecha_informacion');
            $table->dateTime('fecha_creacion')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alarmas');
    }
};
