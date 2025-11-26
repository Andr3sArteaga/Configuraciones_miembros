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
        Schema::create('tipos_incidente', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('codigo', 50)->unique('tipos_incidente_codigo_key');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('color', 7)->nullable();
            $table->string('icono', 50)->nullable();
            $table->boolean('activo')->nullable()->default(true)->index('idx_tipos_incidente_activo');
            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_incidente');
    }
};
