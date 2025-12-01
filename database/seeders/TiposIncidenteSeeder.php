<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposIncidente;

class TiposIncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'codigo' => 'FORESTAL',
                'nombre' => 'Forestal',
                'descripcion' => 'Incendio en áreas forestales o vegetación',
                'color' => '#228B22',
                'icono' => 'tree'
            ],
            [
                'codigo' => 'ESTRUCTURAL',
                'nombre' => 'Estructural',
                'descripcion' => 'Incendio en edificaciones',
                'color' => '#FF6347',
                'icono' => 'building'
            ],
            [
                'codigo' => 'VEHICULAR',
                'nombre' => 'Vehicular',
                'descripcion' => 'Incendio en vehículos',
                'color' => '#FFA500',
                'icono' => 'car'
            ],
            [
                'codigo' => 'INDUSTRIAL',
                'nombre' => 'Industrial',
                'descripcion' => 'Incendio en instalaciones industriales',
                'color' => '#8B0000',
                'icono' => 'industry'
            ],
            [
                'codigo' => 'OTRO',
                'nombre' => 'Otro',
                'descripcion' => 'Otros tipos de incendio',
                'color' => '#808080',
                'icono' => 'fire'
            ],
        ];

        foreach ($tipos as $tipo) {
            TiposIncidente::firstOrCreate(
                ['codigo' => $tipo['codigo']],
                [
                    'nombre' => $tipo['nombre'],
                    'descripcion' => $tipo['descripcion'],
                    'color' => $tipo['color'],
                    'icono' => $tipo['icono']
                ]
            );
        }
    }
}
