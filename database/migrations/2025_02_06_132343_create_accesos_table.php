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
        Schema::create('accesos', function (Blueprint $table) {
            $table->integer('acceso_id', true);
            $table->integer('usuario_id')->nullable()->index('usuario_id');
            $table->integer('proyecto_id')->nullable()->index('proyecto_id');
            $table->integer('subproyecto_id')->nullable()->index('subproyecto_id');
            $table->enum('rol_acceso', ['Global', 'Proyecto', 'Subproyecto'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesos');
    }
};
