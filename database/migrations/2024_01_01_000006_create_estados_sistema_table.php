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
        Schema::create('estados_sistema', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('tabla', 50)->index('idx_estados_sistema_tabla');
            $table->string('codigo', 50);
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('color', 7)->nullable();
            $table->boolean('es_final')->nullable()->default(false);
            $table->integer('orden')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('creado')->nullable()->useCurrent();

            $table->unique(['tabla', 'codigo'], 'estados_sistema_tabla_codigo_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_sistema');
    }
};
