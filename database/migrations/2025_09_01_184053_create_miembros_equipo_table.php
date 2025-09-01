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
        Schema::create('miembros_equipo', function (Blueprint $table) {
            $table->comment('Relación muchos a muchos entre usuarios y equipos');
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->uuid('id_equipo')->nullable()->index('idx_miembros_equipo_equipo');
            $table->uuid('id_usuario')->nullable()->index('idx_miembros_equipo_usuario');
            $table->timestamp('fecha_ingreso')->nullable()->useCurrent();

            $table->unique(['id_equipo', 'id_usuario'], 'miembros_equipo_id_equipo_id_usuario_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_equipo');
    }
};
