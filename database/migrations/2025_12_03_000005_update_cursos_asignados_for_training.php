<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extiende cursos_asignados con metadatos de asignación.
     */
    public function up(): void
    {
        Schema::table('cursos_asignados', function (Blueprint $table) {
            $table->uuid('assigned_by')
                ->nullable()
                ->after('entidad_tipo')
                ->index('idx_cursos_asignados_assigned_by');

            $table->string('assignment_type', 20)
                ->nullable()
                ->after('assigned_by'); // OBLIGATORIO / SUGERIDO

            $table->date('due_date')
                ->nullable()
                ->after('assignment_type');

            $table->string('status', 20)
                ->default('pendiente')
                ->after('due_date')
                ->index('idx_cursos_asignados_status');

            $table->timestamp('acknowledged_at')
                ->nullable()
                ->after('status');

            $table->text('notas')
                ->nullable()
                ->after('acknowledged_at');
        });

        Schema::table('cursos_asignados', function (Blueprint $table) {
            if (Schema::hasTable('usuarios')) {
                $table->foreign('assigned_by', 'cursos_asignados_assigned_by_fkey')
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
        Schema::table('cursos_asignados', function (Blueprint $table) {
            try {
                $table->dropForeign('cursos_asignados_assigned_by_fkey');
            } catch (\Throwable $e) {
                // ignorar si no existe
            }

            foreach ([
                'assigned_by',
                'assignment_type',
                'due_date',
                'status',
                'acknowledged_at',
                'notas',
            ] as $column) {
                if (Schema::hasColumn('cursos_asignados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};


