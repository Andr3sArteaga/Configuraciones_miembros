<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de etapas por curso (course_stages).
     */
    public function up(): void
    {
        Schema::create('course_stages', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('curso_id')->index('idx_course_stages_curso');

            $table->unsignedTinyInteger('stage_number');
            $table->string('titulo_autogenerado', 100);
            $table->string('module_name', 150);
            $table->text('descripcion')->nullable();

            $table->integer('duracion_minutos')->nullable();
            $table->string('delivery_mode', 20)->nullable(); // campo, aula, virtual, mixto
            $table->boolean('is_final_stage')->default(false);
            $table->unsignedTinyInteger('orden')->default(1);

            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            $table->unique(['curso_id', 'stage_number'], 'course_stages_curso_stage_unique');
        });

        Schema::table('course_stages', function (Blueprint $table) {
            if (Schema::hasTable('cursos')) {
                $table->foreign('curso_id', 'course_stages_curso_id_fkey')
                    ->references('id')
                    ->on('cursos')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Revertir cambios.
     */
    public function down(): void
    {
        Schema::table('course_stages', function (Blueprint $table) {
            if (Schema::hasColumn('course_stages', 'curso_id')) {
                try {
                    $table->dropForeign('course_stages_curso_id_fkey');
                } catch (\Throwable $e) {
                    // ignorar si no existe
                }
            }
        });

        Schema::dropIfExists('course_stages');
    }
};


