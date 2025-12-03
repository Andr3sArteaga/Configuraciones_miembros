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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('usuario_id')->index('idx_notifications_usuario');
            
            $table->string('type', 50)->index('idx_notifications_type');
            $table->string('title', 200);
            $table->text('message');
            $table->json('data')->nullable();
            
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasTable('usuarios')) {
                $table->foreign('usuario_id', 'notifications_usuario_id_fkey')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'usuario_id')) {
                try {
                    $table->dropForeign('notifications_usuario_id_fkey');
                } catch (\Throwable $e) {
                    // ignore if doesn't exist
                }
            }
        });

        Schema::dropIfExists('notifications');
    }
};
