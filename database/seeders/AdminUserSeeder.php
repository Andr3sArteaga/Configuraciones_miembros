<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Role;
use App\Models\TiposSangre;
use App\Models\NivelesEntrenamiento;
use App\Models\Genero;
use App\Models\EstadosSistema;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IDs from parametric tables
        $rolAdmin = Role::where('codigo', 'ADMIN')->first();
        $tipoSangre = TiposSangre::where('codigo', 'O+')->first();
        $nivelEntrenamiento = NivelesEntrenamiento::where('nivel', 'Experto')->first();
        $genero = Genero::where('codigo', 'OTRO')->first();
        $estadoActivo = EstadosSistema::where('tabla', 'usuarios')
                                      ->where('codigo', 'ACTIVO')
                                      ->first();

        if (!$rolAdmin || !$tipoSangre || !$nivelEntrenamiento || !$genero || !$estadoActivo) {
            $this->command->error('Error: No se encontraron los registros paramétricos necesarios.');
            $this->command->error('Asegúrate de ejecutar los seeders de parámetros antes del AdminUserSeeder.');
            return;
        }

        // Create admin user if it doesn't exist
        Usuario::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'ci' => '00000000',
                'fecha_nacimiento' => '1990-01-01',
                'genero_id' => $genero->id,
                'telefono' => '00000000',
                'password' => Hash::make('12345678'),
                'tipo_sangre_id' => $tipoSangre->id,
                'nivel_entrenamiento_id' => $nivelEntrenamiento->id,
                'entidad_perteneciente' => 'Sistema Central',
                'rol_id' => $rolAdmin->id,
                'estado_id' => $estadoActivo->id,
                'debe_cambiar_password' => false,
            ]
        );

        $this->command->info('Usuario administrador creado exitosamente.');
        $this->command->info('Email: admin@sistema.com');
        $this->command->info('Contraseña: 12345678');
    }
}
