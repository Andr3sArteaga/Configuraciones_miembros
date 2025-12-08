<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Usuario;
use App\Models\Role as LegacyRole; // Role model now points to legacy_roles

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (example)
        // Permission::create(['name' => 'edit articles']);

        // create roles
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleVoluntario = Role::firstOrCreate(['name' => 'Voluntario']);
        $roleCiudadano = Role::firstOrCreate(['name' => 'Ciudadano']);

        // Migrate existing users
        $usuarios = Usuario::with('role')->get();

        foreach ($usuarios as $usuario) {
            $legacyRole = $usuario->role; // Using the relationship to LegacyRole
            
            if (!$legacyRole) {
                 $usuario->assignRole($roleCiudadano);
                 continue;
            }

            $roleName = strtolower($legacyRole->nombre);
            $roleCodigo = strtolower($legacyRole->codigo);

            if ($roleCodigo === 'admin') {
                $usuario->assignRole($roleAdmin);
            } elseif (in_array($roleCodigo, ['bombero', 'paramedico', 'veterinario', 'voluntario'])) {
                $usuario->assignRole($roleVoluntario);
            } else {
                $usuario->assignRole($roleCiudadano);
            }
        }
    }
}
