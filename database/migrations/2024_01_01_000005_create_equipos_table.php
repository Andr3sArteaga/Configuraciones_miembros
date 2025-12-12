<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombre_equipo', 100);
            //$table->geography('ubicacion', 'point')->nullable(); // eliminado: ubicación heredada de reporte
            $table->integer('cantidad_integrantes')->nullable()->default(0);
            $table->uuid('estado_id')->nullable()->index('idx_equipos_estado');
            // Relación opcional al reporte
            $table->uuid('reporte_id')->nullable()->index('idx_equipos_reporte_id');
            $table->foreign('reporte_id', 'fk_equipos_reporte')->references('id')->on('reportes')->onDelete('set null');

            // Ubicación PostGIS
            $table->geography('ubicacion', 'point')->nullable();
            $table->spatialIndex('ubicacion'); // Índice espacial para consultas rápidas

            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            // Se eliminó índice spatial ubicado en equipos: ubicacion ahora gestionado por reportes
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
