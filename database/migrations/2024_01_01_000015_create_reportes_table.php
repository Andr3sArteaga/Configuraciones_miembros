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
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombre_reportante', 200);
            $table->string('telefono_contacto', 20)->nullable();
            $table->timestamp('fecha_hora')->index('idx_reportes_fecha');
            $table->string('nombre_lugar', 200)->nullable();
            $table->geography('ubicacion', 'point')->nullable();
            $table->uuid('tipo_incidente_id')->nullable()->index('idx_reportes_tipo');
            $table->uuid('gravedad_id')->nullable()->index('idx_reportes_gravedad');
            $table->text('comentario_adicional')->nullable();
            $table->integer('cant_bomberos')->nullable()->default(0);
            $table->integer('cant_paramedicos')->nullable()->default(0);
            $table->integer('cant_veterinarios')->nullable()->default(0);
            $table->integer('cant_autoridades')->nullable()->default(0);
            $table->uuid('estado_id')->nullable()->index('idx_reportes_estado');
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
