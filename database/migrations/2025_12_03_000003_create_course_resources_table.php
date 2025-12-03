<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de recursos por etapa (course_resources).
     */
    public function up(): void
    {
        Schema::create('course_resources', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('stage_id')->index('idx_course_resources_stage');

            $table->string('resource_type', 30); // video, documento, lectura, material_extra
            $table->string('titulo', 150)->nullable();
            $table->string('resource_url', 500)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('requires_ack')->default(false);

            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();
        });

        Schema::table('course_resources', function (Blueprint $table) {
            if (Schema::hasTable('course_stages')) {
                $table->foreign('stage_id', 'course_resources_stage_id_fkey')
                    ->references('id')
                    ->on('course_stages')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Revertir cambios.
     */
    public function down(): void
    {
        Schema::table('course_resources', function (Blueprint $table) {
            if (Schema::hasColumn('course_resources', 'stage_id')) {
                try {
                    $table->dropForeign('course_resources_stage_id_fkey');
                } catch (\Throwable $e) {
                    // ignorar si no existe
                }
            }
        });

        Schema::dropIfExists('course_resources');
    }
};


