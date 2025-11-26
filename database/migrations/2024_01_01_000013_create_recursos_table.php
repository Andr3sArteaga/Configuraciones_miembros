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
        Schema::create('recursos', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('codigo', 50)->nullable()->unique('recursos_codigo_key');
            $table->uuid('tipo_recurso_id')->nullable()->index('idx_recursos_tipo');
            $table->text('descripcion');
            $table->decimal('cantidad', 10)->nullable();
            $table->timestamp('fecha_pedido')->nullable()->useCurrent();
            $table->uuid('estado_id')->nullable()->index('idx_recursos_estado');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->uuid('equipoid')->nullable()->index('idx_recursos_equipo');
            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
