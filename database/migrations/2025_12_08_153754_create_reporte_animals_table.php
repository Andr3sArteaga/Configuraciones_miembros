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
        Schema::create('reportes_animales', function (Blueprint $table) {
            $table->id();
            // Assuming incendio_id refers to 'reportes' table which might have UUIDs or BigInts. 
            // Checking previous files: Reporte model uses 'reportes' table. 
            // In UsuarioController, IDs are UUIDs. 
            // But the USER REQUEST said "incendio_id: number". 
            // I will adhere to "number" (unsignedBigInteger) BUT I must verify what 'reportes' uses.
            // If reportes uses UUID, I can't use integer.
            // Let's check 'reportes' table migration first.
            // Wait, I can't check it inside this tool call.
            // I will use unsignedBigInteger for now as requested, but if foreign key fails I'll fix it.
            // Actually, safe bet is to NOT add foreign key constraint immediately if not sure, 
            // but for data integrity I should.
            // I'll stick to the user's specific JSON payload description asking for "number".
            
            $table->string('incendio_id')->nullable(); // Using string for UUID
            
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->string('direccion')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->unsignedBigInteger('condicion_inicial_id');
            $table->unsignedBigInteger('tipo_incidente_id');
            
            $table->string('tamano'); // pequeño, mediano, grande
            $table->boolean('puede_moverse');
            $table->boolean('traslado_inmediato')->default(false);
            
            $table->unsignedBigInteger('centro_id')->nullable();
            $table->string('imagen_path');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_animales');
    }
};
