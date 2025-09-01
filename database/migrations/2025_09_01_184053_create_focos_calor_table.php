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
        Schema::create('focos_calor', function (Blueprint $table) {
            $table->comment('Datos satelitales de focos de calor detectados');
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('confidence')->nullable();
            $table->date('acq_date')->index('idx_focos_fecha');
            $table->time('acq_time');
            $table->decimal('bright_ti4')->nullable();
            $table->decimal('bright_ti5')->nullable();
            $table->decimal('frp')->nullable();
            $table->timestamp('creado')->nullable()->useCurrent();

            $table->index(['latitude', 'longitude'], 'idx_focos_coordenadas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('focos_calor');
    }
};
