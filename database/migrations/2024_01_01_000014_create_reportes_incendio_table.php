<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes_incendio', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombre_incidente', 200);
            $table->boolean('controlado')->nullable()->default(false);
            $table->decimal('extension', 10)->nullable();
            $table->uuid('condicion_climatica_id')->nullable();
            $table->text('equipos_en_uso')->nullable();
            $table->integer('numero_bomberos')->nullable();
            $table->boolean('necesita_mas_bomberos')->nullable()->default(false);
            $table->text('apoyo_externo')->nullable();
            $table->text('comentario_adicional')->nullable();
            $table->timestamp('fecha_creacion')->nullable()->useCurrent();
            $table->uuid('id_usuario_creador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_incendio');
    }
};
