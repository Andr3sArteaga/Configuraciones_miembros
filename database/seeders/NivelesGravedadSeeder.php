<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NivelesGravedad;

class NivelesGravedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            [
                'codigo' => 'LEVE',
                'nombre' => 'Controlado',
                'descripcion' => 'Situación controlable con recursos mínimos',
                'orden' => 1,
                'color' => '#90EE90'
            ],
            [
                'codigo' => 'MODERADO',
                'nombre' => 'Contenido',
                'descripcion' => 'Requiere atención pero no es crítico',
                'orden' => 2,
                'color' => '#FFD700'
            ],
            [
                'codigo' => 'GRAVE',
                'nombre' => 'Activo',
                'descripcion' => 'Situación seria que requiere recursos significativos',
                'orden' => 3,
                'color' => '#FF8C00'
            ],
            [
                'codigo' => 'CRITICO',
                'nombre' => 'Fuera de control',
                'descripcion' => 'Emergencia máxima, todos los recursos disponibles',
                'orden' => 4,
                'color' => '#DC143C'
            ],
        ];

        foreach ($niveles as $nivel) {
            NivelesGravedad::firstOrCreate(
                ['codigo' => $nivel['codigo']],
                [
                    'nombre' => $nivel['nombre'],
                    'descripcion' => $nivel['descripcion'],
                    'orden' => $nivel['orden'],
                    'color' => $nivel['color']
                ]
            );
        }
    }
}
