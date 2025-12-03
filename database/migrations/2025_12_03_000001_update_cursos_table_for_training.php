<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extiende la tabla cursos para soportar el sistema de módulos de entrenamiento.
     */
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Metadatos básicos del curso
            $table->string('slug', 200)->nullable()->unique('cursos_slug_unique');
            $table->text('objetivos')->nullable();

            // Programación
            $table->date('inicio_programado')->nullable()->index('idx_cursos_inicio_programado');
            $table->date('fin_programado')->nullable()->index('idx_cursos_fin_programado');
            $table->integer('max_participantes')->nullable();

            // Estado y visibilidad
            $table->string('estado', 50)
                ->default('draft')
                ->index('idx_cursos_estado');
            $table->string('visibilidad', 50)
                ->default('usuarios')
                ->index('idx_cursos_visibilidad');

            // Nivel mínimo sugerido
            $table->uuid('nivel_requerido_id')
                ->nullable()
                ->index('idx_cursos_nivel_requerido');

            // Auditoría básica (creador / aprobador)
            $table->uuid('created_by')
                ->nullable()
                ->index('idx_cursos_created_by');
            $table->uuid('updated_by')
                ->nullable()
                ->index('idx_cursos_updated_by');
            $table->uuid('approved_by')
                ->nullable()
                ->index('idx_cursos_approved_by');
            $table->timestamp('fecha_aprobacion')->nullable();

            // Timestamp de actualización (manteniendo convención español)
            if (!Schema::hasColumn('cursos', 'actualizado')) {
                $table->timestamp('actualizado')->nullable()->useCurrent();
            }
        });

        // Claves foráneas en una segunda llamada para evitar problemas de orden
        Schema::table('cursos', function (Blueprint $table) {
            // Nivel de entrenamiento mínimo sugerido
            if (Schema::hasTable('niveles_entrenamiento')) {
                $table->foreign('nivel_requerido_id', 'cursos_nivel_requerido_fkey')
                    ->references('id')
                    ->on('niveles_entrenamiento')
                    ->onDelete('set null');
            }

            // Usuarios del sistema (creador / actualizador / aprobador)
            if (Schema::hasTable('usuarios')) {
                $table->foreign('created_by', 'cursos_created_by_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('set null');

                $table->foreign('updated_by', 'cursos_updated_by_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('set null');

                $table->foreign('approved_by', 'cursos_approved_by_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Revertir cambios.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Quitar FKs primero
            $fkNames = [
                'cursos_nivel_requerido_fkey',
                'cursos_created_by_fkey',
                'cursos_updated_by_fkey',
                'cursos_approved_by_fkey',
            ];

            foreach ($fkNames as $fk) {
                if ($this->hasForeignKey($table, 'cursos', $fk)) {
                    $table->dropForeign($fk);
                }
            }
        });

        Schema::table('cursos', function (Blueprint $table) {
            if (Schema::hasColumn('cursos', 'slug')) {
                $table->dropUnique('cursos_slug_unique');
                $table->dropColumn('slug');
            }

            foreach ([
                'objetivos',
                'inicio_programado',
                'fin_programado',
                'max_participantes',
                'estado',
                'visibilidad',
                'nivel_requerido_id',
                'created_by',
                'updated_by',
                'approved_by',
                'fecha_aprobacion',
                'actualizado',
            ] as $column) {
                if (Schema::hasColumn('cursos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Helper para comprobar existencia de FK sin lanzar errores.
     */
    private function hasForeignKey(Blueprint $table, string $tableName, string $foreignKeyName): bool
    {
        try {
            $connection = Schema::getConnection();
            $schemaManager = $connection->getDoctrineSchemaManager();
            $doctrineTable = $schemaManager->listTableDetails($connection->getTablePrefix() . $tableName);
            return $doctrineTable->hasForeignKey($foreignKeyName);
        } catch (\Throwable $e) {
            return false;
        }
    }
};


