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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign(['estado_id'], 'usuarios_estado_id_fkey')->references(['id'])->on('estados_sistema')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['genero_id'], 'usuarios_genero_id_fkey')->references(['id'])->on('generos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['nivel_entrenamiento_id'], 'usuarios_nivel_entrenamiento_id_fkey')->references(['id'])->on('niveles_entrenamiento')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['rol_id'], 'usuarios_rol_id_fkey')->references(['id'])->on('roles')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tipo_sangre_id'], 'usuarios_tipo_sangre_id_fkey')->references(['id'])->on('tipos_sangre')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('usuarios_estado_id_fkey');
            $table->dropForeign('usuarios_genero_id_fkey');
            $table->dropForeign('usuarios_nivel_entrenamiento_id_fkey');
            $table->dropForeign('usuarios_rol_id_fkey');
            $table->dropForeign('usuarios_tipo_sangre_id_fkey');
        });
    }
};
