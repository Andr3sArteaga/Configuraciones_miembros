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
        Schema::create('equipos', function (Blueprint $table) {
            $table->comment('Tabla de equipos de bomberos con ubicación geográfica');
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->string('nombre_equipo', 100);
            $table->geography('ubicacion', 'point')->nullable()->comment('Ubicación geográfica del equipo usando PostGIS');
            $table->integer('cantidad_integrantes')->nullable()->default(0);
            $table->uuid('id_lider_equipo')->nullable()->index('idx_equipos_lider');
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'EN_MISION'])->nullable()->default('ACTIVO')->index('idx_equipos_estado');
            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            $table->spatialIndex(['ubicacion'], 'idx_equipos_ubicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
