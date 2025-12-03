<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cambiar el valor por defecto de estado en cursos para que no sea 'draft'.
     */
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Establecer 'activo' como estado por defecto
            $table->string('estado', 50)->default('activo')->change();
        });
    }

    /**
     * Revertir cambio de estado por defecto.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('estado', 50)->default('draft')->change();
        });
    }
};


