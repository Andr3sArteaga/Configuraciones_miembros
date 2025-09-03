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
        Schema::create('reportes', function (Blueprint $table) {
            $table->comment('Reportes rápidos de incendios realizados por ciudadanos');
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombre_reportante', 200);
            $table->string('telefono_contacto', 20)->nullable();
            $table->timestamp('fecha_hora')->index('idx_reportes_fecha');
            $table->string('nombre_lugar', 200)->nullable();
            $table->geography('ubicacion', 'point')->nullable()->comment('Ubicación geográfica del incendio reportado usando PostGIS');
            $table->enum('tipo_incendio', ['Forestal', 'Estructural', 'Vehicular', 'Industrial', 'Otro'])->nullable()->index('idx_reportes_tipo');
            $table->enum('gravedad_incendio', ['Leve', 'Moderado', 'Grave', 'Crítico'])->nullable()->index('idx_reportes_gravedad');
            $table->text('comentario_adicional')->nullable();
            $table->integer('cant_bomberos')->nullable()->default(0);
            $table->integer('cant_paramedicos')->nullable()->default(0);
            $table->integer('cant_veterinarios')->nullable()->default(0);
            $table->integer('cant_autoridades')->nullable()->default(0);
            $table->enum('estado', ['PENDIENTE', 'EN_PROCESO', 'CONTROLADO', 'EXTINGUIDO'])->nullable()->default('PENDIENTE');
            $table->timestamp('creado')->nullable()->useCurrent();

            $table->spatialIndex(['ubicacion'], 'idx_reportes_ubicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
