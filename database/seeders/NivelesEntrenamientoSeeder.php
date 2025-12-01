<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NivelesEntrenamiento;

class NivelesEntrenamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            ['nivel' => 'Básico', 'descripcion' => 'Entrenamiento inicial en operaciones básicas', 'orden' => 1],
            ['nivel' => 'Intermedio', 'descripcion' => 'Conocimientos avanzados en técnicas de combate', 'orden' => 2],
            ['nivel' => 'Avanzado', 'descripcion' => 'Especialización en áreas específicas', 'orden' => 3],
            ['nivel' => 'Experto', 'descripcion' => 'Máximo nivel de capacitación y experiencia', 'orden' => 4],
        ];

        foreach ($niveles as $nivel) {
            NivelesEntrenamiento::firstOrCreate(
                ['nivel' => $nivel['nivel']],
                [
                    'descripcion' => $nivel['descripcion'],
                    'orden' => $nivel['orden']
                ]
            );
        }
    }
}
