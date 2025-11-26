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
        Schema::table('reportes_incendio', function (Blueprint $table) {
            $table->foreign(['condicion_climatica_id'], 'reportes_incendio_condicion_climatica_id_fkey')->references(['id'])->on('condiciones_climaticas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_usuario_creador'], 'reportes_incendio_id_usuario_creador_fkey')->references(['id'])->on('usuarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reportes_incendio', function (Blueprint $table) {
            $table->dropForeign('reportes_incendio_condicion_climatica_id_fkey');
            $table->dropForeign('reportes_incendio_id_usuario_creador_fkey');
        });
    }
};
