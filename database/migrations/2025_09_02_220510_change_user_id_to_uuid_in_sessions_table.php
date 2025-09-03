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
        Schema::table('sessions', function (Blueprint $table) {
            // Primero eliminamos la columna user_id existente
            $table->dropColumn('user_id');
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            // Luego agregamos la columna user_id como UUID
            $table->uuid('user_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Eliminamos la columna UUID
            $table->dropColumn('user_id');
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            // Restauramos la columna como bigint
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }
};
