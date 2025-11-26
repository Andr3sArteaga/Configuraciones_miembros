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
        Schema::table('cursos_asignados', function (Blueprint $table) {
            $table->foreign(['curso_id'], 'cursos_asignados_curso_id_fkey')->references(['id'])->on('cursos')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos_asignados', function (Blueprint $table) {
            $table->dropForeign('cursos_asignados_curso_id_fkey');
        });
    }
};
