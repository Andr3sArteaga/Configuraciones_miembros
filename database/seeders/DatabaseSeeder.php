<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando la siembra de datos...');

        // Parametric data seeders (must be run first)
        $this->command->info('📊 Poblando tablas paramétricas...');

        $this->call([
            TiposSangreSeeder::class,
            NivelesEntrenamientoSeeder::class,
            GenerosSeeder::class,
            RolesSeeder::class,
            TiposIncidenteSeeder::class,
            NivelesGravedadSeeder::class,
            TiposRecursoSeeder::class,
            EstadosSistemaSeeder::class,
            CondicionesClimaticasSeeder::class,
        ]);

        $this->command->info('✅ Tablas paramétricas pobladas exitosamente.');

        // Create admin user (depends on parametric data)
        $this->command->info('👤 Creando usuario administrador...');
        $this->call(AdminUserSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // Optional: Other seeders
        $this->command->info('📋 Poblando reportes de ejemplo...');
        $this->call(ReportesSeeder::class);
        // $this->call(NoticiasSeeder::class);

        $this->command->info('🎉 ¡Siembra completada exitosamente!');
    }
}
