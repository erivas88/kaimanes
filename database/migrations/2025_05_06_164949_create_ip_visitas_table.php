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
        Schema::create('ip_visitas', function (Blueprint $table) {
            $table->date('fecha');
            $table->ipAddress('ip');
            $table->primary(['fecha', 'ip']); // clave compuesta: no permite repetir la IP por día
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_visitas');
    }
};
