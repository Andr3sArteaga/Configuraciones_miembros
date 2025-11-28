<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear tabla 'inscrito'
        Schema::create('inscrito', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->string('nombres', 150); // NOT NULL por defecto
            $table->string('apellidos', 150); // NOT NULL
            $table->string('ci', 50)->unique(); // UNIQUE + NOT NULL
            $table->string('telefono', 50)->nullable();
            $table->string('correo', 150)->unique();
            $table->timestamp('fecha_registro')->useCurrent();
        });


        // Asegurarse que la columna 'entidad_tipo' existe en cursos_asignados
        // Si ya existe, no hacemos nada. Si quieres agregarla nueva, sería así:
        /*
        Schema::table('cursos_asignados', function (Blueprint $table) {
            $table->string('entidad_tipo', 50)->after('entidad_id');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscrito');

        // Si agregaste la columna en up(), puedes eliminarla aquí
        /*
        Schema::table('cursos_asignados', function (Blueprint $table) {
            $table->dropColumn('entidad_tipo');
        });
        */
    }
};
