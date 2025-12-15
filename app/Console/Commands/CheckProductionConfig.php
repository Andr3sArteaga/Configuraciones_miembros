<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckProductionConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:check-production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la configuración de producción para autenticación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando Configuración de Producción...');
        $this->newLine();

        $allChecks = true;

        // 1. Verificar APP_KEY
        $allChecks = $this->checkAppKey() && $allChecks;
        $this->newLine();

        // 2. Verificar extensiones PostgreSQL
        $allChecks = $this->checkPostgresExtensions() && $allChecks;
        $this->newLine();

        // 3. Verificar tabla sessions
        $allChecks = $this->checkSessionsTable() && $allChecks;
        $this->newLine();

        // 4. Verificar tabla usuarios
        $allChecks = $this->checkUsuariosTable() && $allChecks;
        $this->newLine();

        // 5. Verificar configuración de sesión
        $allChecks = $this->checkSessionConfig() && $allChecks;
        $this->newLine();

        // 6. Verificar configuración de autenticación
        $allChecks = $this->checkAuthConfig() && $allChecks;
        $this->newLine();

        if ($allChecks) {
            $this->info('✅ Todas las verificaciones pasaron correctamente');
            return 0;
        } else {
            $this->error('❌ Algunas verificaciones fallaron. Revisa los detalles arriba.');
            return 1;
        }
    }

    private function checkAppKey(): bool
    {
        $this->info('1️⃣  Verificando APP_KEY...');
        
        $appKey = config('app.key');
        
        if (empty($appKey)) {
            $this->error('   ❌ APP_KEY no está configurado');
            $this->warn('   💡 Solución: Ejecuta "php artisan key:generate" o configura APP_KEY en .env');
            return false;
        }
        
        $this->line('   ✅ APP_KEY está configurado: ' . substr($appKey, 0, 20) . '...');
        return true;
    }

    private function checkPostgresExtensions(): bool
    {
        $this->info('2️⃣  Verificando Extensiones PostgreSQL...');
        
        try {
            $extensions = DB::select("SELECT extname FROM pg_extension WHERE extname IN ('uuid-ossp', 'postgis')");
            $extensionNames = array_column($extensions, 'extname');
            
            $hasUuid = in_array('uuid-ossp', $extensionNames);
            $hasPostgis = in_array('postgis', $extensionNames);
            
            if (!$hasUuid) {
                $this->error('   ❌ Extensión "uuid-ossp" no está habilitada');
                $this->warn('   💡 Solución: Ejecuta "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";" en PostgreSQL');
                return false;
            }
            
            if (!$hasPostgis) {
                $this->warn('   ⚠️  Extensión "postgis" no está habilitada (puede ser necesaria)');
            }
            
            $this->line('   ✅ uuid-ossp: Habilitada');
            $this->line('   ' . ($hasPostgis ? '✅' : '⚠️ ') . ' postgis: ' . ($hasPostgis ? 'Habilitada' : 'No habilitada'));
            
            return $hasUuid;
        } catch (\Exception $e) {
            $this->error('   ❌ Error al verificar extensiones: ' . $e->getMessage());
            return false;
        }
    }

    private function checkSessionsTable(): bool
    {
        $this->info('3️⃣  Verificando Tabla sessions...');
        
        try {
            if (!Schema::hasTable('sessions')) {
                $this->error('   ❌ La tabla "sessions" no existe');
                $this->warn('   💡 Solución: Ejecuta "php artisan migrate"');
                return false;
            }
            
            $columns = Schema::getColumnListing('sessions');
            $requiredColumns = ['id', 'user_id', 'payload', 'last_activity'];
            $missingColumns = array_diff($requiredColumns, $columns);
            
            if (!empty($missingColumns)) {
                $this->error('   ❌ Faltan columnas en la tabla sessions: ' . implode(', ', $missingColumns));
                return false;
            }
            
            $sessionCount = DB::table('sessions')->count();
            
            $this->line('   ✅ Tabla sessions existe con estructura correcta');
            $this->line('   📊 Sesiones activas: ' . $sessionCount);
            
            return true;
        } catch (\Exception $e) {
            $this->error('   ❌ Error al verificar tabla sessions: ' . $e->getMessage());
            return false;
        }
    }

    private function checkUsuariosTable(): bool
    {
        $this->info('4️⃣  Verificando Tabla usuarios...');
        
        try {
            if (!Schema::hasTable('usuarios')) {
                $this->error('   ❌ La tabla "usuarios" no existe');
                $this->warn('   💡 Solución: Ejecuta "php artisan migrate"');
                return false;
            }
            
            $columns = Schema::getColumnListing('usuarios');
            $requiredColumns = ['id', 'email', 'password'];
            $missingColumns = array_diff($requiredColumns, $columns);
            
            if (!empty($missingColumns)) {
                $this->error('   ❌ Faltan columnas en la tabla usuarios: ' . implode(', ', $missingColumns));
                return false;
            }
            
            $userCount = DB::table('usuarios')->count();
            
            if ($userCount === 0) {
                $this->warn('   ⚠️  No hay usuarios en la base de datos');
                $this->warn('   💡 Solución: Ejecuta "php artisan db:seed" o crea usuarios manualmente');
            }
            
            $this->line('   ✅ Tabla usuarios existe con estructura correcta');
            $this->line('   👥 Total de usuarios: ' . $userCount);
            
            // Mostrar un usuario de ejemplo (sin password)
            if ($userCount > 0) {
                $sampleUser = DB::table('usuarios')->select('id', 'email', 'nombre', 'apellido')->first();
                $this->line('   📝 Usuario de ejemplo: ' . $sampleUser->email . ' (' . $sampleUser->nombre . ' ' . $sampleUser->apellido . ')');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->error('   ❌ Error al verificar tabla usuarios: ' . $e->getMessage());
            return false;
        }
    }

    private function checkSessionConfig(): bool
    {
        $this->info('5️⃣  Verificando Configuración de Sesión...');
        
        $driver = config('session.driver');
        $lifetime = config('session.lifetime');
        $domain = config('session.domain');
        $secure = config('session.secure');
        $sameSite = config('session.same_site');
        
        $this->line('   📋 Driver: ' . $driver);
        $this->line('   ⏱️  Lifetime: ' . $lifetime . ' minutos');
        $this->line('   🌐 Domain: ' . ($domain ?? 'null'));
        $this->line('   🔒 Secure: ' . ($secure ? 'true' : 'false'));
        $this->line('   🍪 SameSite: ' . $sameSite);
        
        if ($driver !== 'database') {
            $this->warn('   ⚠️  SESSION_DRIVER no es "database" (actual: ' . $driver . ')');
            $this->warn('   💡 Considera usar "database" para mayor confiabilidad en producción');
        }
        
        return true;
    }

    private function checkAuthConfig(): bool
    {
        $this->info('6️⃣  Verificando Configuración de Autenticación...');
        
        $guard = config('auth.defaults.guard');
        $provider = config('auth.guards.' . $guard . '.provider');
        $model = config('auth.providers.' . $provider . '.model');
        
        $this->line('   🛡️  Guard: ' . $guard);
        $this->line('   👤 Provider: ' . $provider);
        $this->line('   📦 Model: ' . $model);
        
        // Verificar que el modelo existe
        if (!class_exists($model)) {
            $this->error('   ❌ El modelo de autenticación no existe: ' . $model);
            return false;
        }
        
        $this->line('   ✅ Configuración de autenticación correcta');
        
        return true;
    }
}
