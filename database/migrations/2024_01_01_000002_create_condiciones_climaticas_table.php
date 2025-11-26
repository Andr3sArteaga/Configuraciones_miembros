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
        Schema::create('condiciones_climaticas', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('codigo', 50)->unique('condiciones_climaticas_codigo_key');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('factor_riesgo')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('creado')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condiciones_climaticas');
    }
};
