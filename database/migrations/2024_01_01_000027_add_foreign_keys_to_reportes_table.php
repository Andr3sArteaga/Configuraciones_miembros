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
        Schema::table('reportes', function (Blueprint $table) {
            $table->foreign(['estado_id'], 'reportes_estado_id_fkey')->references(['id'])->on('estados_sistema')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['gravedad_id'], 'reportes_gravedad_id_fkey')->references(['id'])->on('niveles_gravedad')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tipo_incidente_id'], 'reportes_tipo_incidente_id_fkey')->references(['id'])->on('tipos_incidente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropForeign('reportes_estado_id_fkey');
            $table->dropForeign('reportes_gravedad_id_fkey');
            $table->dropForeign('reportes_tipo_incidente_id_fkey');
        });
    }
};
