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
        Schema::create('planessim', function (Blueprint $table) {
            $table->integer('sim_id', true);
            $table->string('numero_sim', 20)->unique('numero_sim');
            $table->string('compania', 100)->nullable();
            $table->string('plan_datos', 100)->nullable();
            $table->integer('capacidad_transmision')->nullable();
            $table->decimal('costo', 10)->nullable();
            $table->date('fecha_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planessim');
    }
};
