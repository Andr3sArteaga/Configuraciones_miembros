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
        Schema::table('miembros_equipo', function (Blueprint $table) {
            $table->foreign(['id_equipo'], 'miembros_equipo_id_equipo_fkey')->references(['id'])->on('equipos')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_usuario'], 'miembros_equipo_id_usuario_fkey')->references(['id'])->on('usuarios')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros_equipo', function (Blueprint $table) {
            $table->dropForeign('miembros_equipo_id_equipo_fkey');
            $table->dropForeign('miembros_equipo_id_usuario_fkey');
        });
    }
};
