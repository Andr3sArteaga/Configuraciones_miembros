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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id')->nullable();
            $table->text('ci_usuario')->nullable();
            $table->string('accion');
            $table->string('modulo');
            $table->string('entidad_tipo')->nullable();
            $table->uuid('entidad_id')->nullable();
            $table->text('descripcion');
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('metodo_http')->nullable();
            $table->string('ruta')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios')
                ->onDelete('set null');

            // Índices para búsquedas frecuentes
            $table->index(['usuario_id', 'created_at']);
            $table->index(['ci_usuario', 'created_at']);
            $table->index(['accion', 'created_at']);
            $table->index(['modulo', 'created_at']);
            $table->index('entidad_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
