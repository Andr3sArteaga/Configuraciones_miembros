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
        Schema::table('recursos', function (Blueprint $table) {
            $table->foreign(['equipoid'], 'recursos_equipoid_fkey')->references(['id'])->on('equipos')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['estado_id'], 'recursos_estado_id_fkey')->references(['id'])->on('estados_sistema')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tipo_recurso_id'], 'recursos_tipo_recurso_id_fkey')->references(['id'])->on('tipos_recurso')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->dropForeign('recursos_equipoid_fkey');
            $table->dropForeign('recursos_estado_id_fkey');
            $table->dropForeign('recursos_tipo_recurso_id_fkey');
        });
    }
};
