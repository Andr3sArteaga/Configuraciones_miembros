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
        // First, remove duplicates by keeping only the most recent one for each URL
        DB::statement("
            DELETE FROM noticias_incendios a
            USING noticias_incendios b
            WHERE a.id < b.id
            AND a.url = b.url
            AND a.url IS NOT NULL
        ");

        // Now add unique constraint
        Schema::table('noticias_incendios', function (Blueprint $table) {
            $table->unique('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias_incendios', function (Blueprint $table) {
            $table->dropUnique(['url']);
        });
    }
};
