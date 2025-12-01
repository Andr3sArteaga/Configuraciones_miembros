<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposRecurso;

class TiposRecursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'codigo' => 'AGUA',
                'nombre' => 'Agua',
                'categoria' => 'Líquidos',
                'descripcion' => 'Agua para combate de incendios',
                'unidad_medida' => 'Litros'
            ],
            [
                'codigo' => 'RETARDANTE',
                'nombre' => 'Retardante',
                'categoria' => 'Químicos',
                'descripcion' => 'Retardante de fuego',
                'unidad_medida' => 'Litros'
            ],
            [
                'codigo' => 'COMBUSTIBLE',
                'nombre' => 'Combustible',
                'categoria' => 'Energía',
                'descripcion' => 'Combustible para vehículos',
                'unidad_medida' => 'Litros'
            ],
            [
                'codigo' => 'HERRAMIENTA_MANUAL',
                'nombre' => 'Herramienta Manual',
                'categoria' => 'Equipamiento',
                'descripcion' => 'Herramientas manuales',
                'unidad_medida' => 'Unidad'
            ],
            [
                'codigo' => 'EQUIPO_PROTECCION',
                'nombre' => 'Equipo de Protección',
                'categoria' => 'Seguridad',
                'descripcion' => 'EPP para bomberos',
                'unidad_medida' => 'Unidad'
            ],
            [
                'codigo' => 'VEHICULO',
                'nombre' => 'Vehículo',
                'categoria' => 'Transporte',
                'descripcion' => 'Vehículos de emergencia',
                'unidad_medida' => 'Unidad'
            ],
            [
                'codigo' => 'MEDICAMENTO',
                'nombre' => 'Medicamento',
                'categoria' => 'Médico',
                'descripcion' => 'Suministros médicos',
                'unidad_medida' => 'Unidad'
            ],
            [
                'codigo' => 'ALIMENTO',
                'nombre' => 'Alimento',
                'categoria' => 'Logística',
                'descripcion' => 'Alimentación para personal',
                'unidad_medida' => 'Kilogramos'
            ],
        ];

        foreach ($tipos as $tipo) {
            TiposRecurso::firstOrCreate(
                ['codigo' => $tipo['codigo']],
                [
                    'nombre' => $tipo['nombre'],
                    'categoria' => $tipo['categoria'],
                    'descripcion' => $tipo['descripcion'],
                    'unidad_medida' => $tipo['unidad_medida']
                ]
            );
        }
    }
}
