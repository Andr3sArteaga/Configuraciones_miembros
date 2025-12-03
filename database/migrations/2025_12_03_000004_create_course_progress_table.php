<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de progreso por usuario y etapa (course_progress).
     */
    public function up(): void
    {
        Schema::create('course_progress', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();

            $table->uuid('curso_id')->index('idx_course_progress_curso');
            $table->uuid('course_stage_id')->index('idx_course_progress_stage');
            $table->uuid('usuario_id')->index('idx_course_progress_usuario');
            $table->uuid('assignment_id')->nullable()->index('idx_course_progress_assignment');

            $table->string('estado', 20)->default('no_iniciado'); // no_iniciado, en_proceso, completado, reprobado
            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->string('evidence_path', 500)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->uuid('reviewed_by')->nullable()->index('idx_course_progress_reviewed_by');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            $table->unique(['usuario_id', 'course_stage_id'], 'course_progress_usuario_stage_unique');
        });

        Schema::table('course_progress', function (Blueprint $table) {
            if (Schema::hasTable('cursos')) {
                $table->foreign('curso_id', 'course_progress_curso_id_fkey')
                    ->references('id')
                    ->on('cursos')
                    ->onDelete('cascade');
            }

            if (Schema::hasTable('course_stages')) {
                $table->foreign('course_stage_id', 'course_progress_stage_id_fkey')
                    ->references('id')
                    ->on('course_stages')
                    ->onDelete('cascade');
            }

            if (Schema::hasTable('usuarios')) {
                $table->foreign('usuario_id', 'course_progress_usuario_id_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

                $table->foreign('reviewed_by', 'course_progress_reviewed_by_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('set null');
            }

            if (Schema::hasTable('cursos_asignados')) {
                $table->foreign('assignment_id', 'course_progress_assignment_id_fkey')
                    ->references('id')
                    ->on('cursos_asignados')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Revertir cambios.
     */
    public function down(): void
    {
        Schema::table('course_progress', function (Blueprint $table) {
            foreach ([
                'course_progress_curso_id_fkey',
                'course_progress_stage_id_fkey',
                'course_progress_usuario_id_fkey',
                'course_progress_reviewed_by_fkey',
                'course_progress_assignment_id_fkey',
            ] as $fk) {
                try {
                    $table->dropForeign($fk);
                } catch (\Throwable $e) {
                    // ignorar si no existe
                }
            }
        });

        Schema::dropIfExists('course_progress');
    }
};


