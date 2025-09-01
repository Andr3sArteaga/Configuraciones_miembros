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
        Schema::create('comunarios_apoyo', function (Blueprint $table) {
            $table->comment('Comunarios que apoyan a los equipos de bomberos');
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->string('nombre', 200);
            $table->integer('edad')->nullable();
            $table->string('entidad_perteneciente', 200)->nullable();
            $table->uuid('equipoid')->nullable();
            $table->timestamp('creado')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunarios_apoyo');
    }
};
