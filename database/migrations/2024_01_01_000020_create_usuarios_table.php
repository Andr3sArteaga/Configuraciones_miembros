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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('ci', 20)->index('idx_usuarios_ci');
            $table->date('fecha_nacimiento');
            $table->uuid('genero_id')->nullable()->index('idx_usuarios_genero');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->unique('usuarios_email_key');
            $table->string('password');
            $table->uuid('tipo_sangre_id')->nullable();
            $table->uuid('nivel_entrenamiento_id')->nullable();
            $table->string('entidad_perteneciente', 200)->nullable();
            $table->uuid('rol_id')->nullable()->index('idx_usuarios_rol');
            $table->uuid('estado_id')->nullable()->index('idx_usuarios_estado');
            $table->boolean('debe_cambiar_password')->nullable()->default(true);
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expires')->nullable();
            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            $table->unique(['ci'], 'usuarios_ci_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
