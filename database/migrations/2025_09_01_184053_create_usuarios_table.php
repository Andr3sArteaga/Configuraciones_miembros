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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->comment('Tabla de usuarios del sistema (bomberos, paramédicos, veterinarios, etc.)');
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('ci', 20)->index('idx_usuarios_ci');
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->index('idx_usuarios_email');
            $table->string('password');
            $table->enum('tipo_de_sangre', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->enum('nivel_de_entrenamiento', ['Básico', 'Intermedio', 'Avanzado', 'Experto'])->nullable();
            $table->string('entidad_perteneciente', 200)->nullable();
            $table->enum('rol', ['ADMIN', 'COORDINADOR', 'BOMBERO', 'PARAMEDICO', 'VETERINARIO'])->nullable()->default('BOMBERO')->index('idx_usuarios_rol');
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'PENDIENTE', 'ELIMINACION_SOLICITADA'])->nullable()->default('PENDIENTE')->index('idx_usuarios_estado');
            $table->boolean('debe_cambiar_password')->nullable()->default(true);
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expires')->nullable();
            $table->timestamp('creado')->nullable()->useCurrent();
            $table->timestamp('actualizado')->nullable()->useCurrent();

            $table->unique(['ci'], 'usuarios_ci_key');
            $table->unique(['email'], 'usuarios_email_key');
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
