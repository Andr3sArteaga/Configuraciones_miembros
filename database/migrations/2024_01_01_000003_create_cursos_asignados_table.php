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
        Schema::create('cursos_asignados', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('curso_id')->nullable()->index('idx_cursos_asignados_curso');
            $table->uuid('entidad_id')->index('idx_cursos_asignados_entidad');
            $table->string('entidad_tipo', 50)->index('idx_cursos_asignados_tipo');
            $table->timestamp('fecha_asignacion')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos_asignados');
    }
};
