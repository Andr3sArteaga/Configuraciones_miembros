<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Enable PostGIS extension for geospatial data
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
            \Log::info('PostGIS extension enabled successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to enable PostGIS extension: ' . $e->getMessage());
            // Don't throw - allow migration to continue
        }

        try {
            // Enable UUID extension for UUID generation
            DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            \Log::info('UUID-OSSP extension enabled successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to enable UUID-OSSP extension: ' . $e->getMessage());
            throw $e; // This one is critical, so we throw
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop extensions as they might be used by other databases
        // DB::statement('DROP EXTENSION IF EXISTS postgis');
        // DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp"');
    }
};
