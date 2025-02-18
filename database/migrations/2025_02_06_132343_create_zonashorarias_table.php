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
        Schema::create('zonashorarias', function (Blueprint $table) {
            $table->integer('zona_id', true);
            $table->string('nombre_zona');
            $table->integer('offset_utc_minutos');
            $table->date('inicio_vigencia');
            $table->date('fin_vigencia')->nullable();
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonashorarias');
    }
};
