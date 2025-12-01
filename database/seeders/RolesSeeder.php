<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'codigo' => 'ADMIN',
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema',
                'permisos' => ['all' => true]
            ],
            [
                'codigo' => 'COORDINADOR',
                'nombre' => 'Coordinador',
                'descripcion' => 'Gestión de equipos y operaciones',
                'permisos' => ['teams' => true, 'reports' => true]
            ],
            [
                'codigo' => 'BOMBERO',
                'nombre' => 'Bombero',
                'descripcion' => 'Personal operativo de combate',
                'permisos' => ['operations' => true]
            ],
            [
                'codigo' => 'PARAMEDICO',
                'nombre' => 'Paramédico',
                'descripcion' => 'Personal médico de emergencia',
                'permisos' => ['medical' => true]
            ],
            [
                'codigo' => 'VETERINARIO',
                'nombre' => 'Veterinario',
                'descripcion' => 'Atención de fauna afectada',
                'permisos' => ['veterinary' => true]
            ],
        ];

        foreach ($roles as $rol) {
            Role::firstOrCreate(
                ['codigo' => $rol['codigo']],
                [
                    'nombre' => $rol['nombre'],
                    'descripcion' => $rol['descripcion'],
                    'permisos' => $rol['permisos']
                ]
            );
        }
    }
}
