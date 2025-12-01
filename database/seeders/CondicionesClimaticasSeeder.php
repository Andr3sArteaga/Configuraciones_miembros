<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CondicionesClimatica;

class CondicionesClimaticasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condiciones = [
            [
                'codigo' => 'SOLEADO',
                'nombre' => 'Soleado',
                'descripcion' => 'Cielo despejado, alta radiación solar',
                'factor_riesgo' => 8
            ],
            [
                'codigo' => 'NUBLADO',
                'nombre' => 'Nublado',
                'descripcion' => 'Cielo cubierto',
                'factor_riesgo' => 4
            ],
            [
                'codigo' => 'LLUVIOSO',
                'nombre' => 'Lluvioso',
                'descripcion' => 'Precipitaciones activas',
                'factor_riesgo' => 2
            ],
            [
                'codigo' => 'VENTOSO',
                'nombre' => 'Ventoso',
                'descripcion' => 'Vientos fuertes',
                'factor_riesgo' => 9
            ],
            [
                'codigo' => 'SECO',
                'nombre' => 'Seco',
                'descripcion' => 'Baja humedad relativa',
                'factor_riesgo' => 9
            ],
            [
                'codigo' => 'HUMEDO',
                'nombre' => 'Húmedo',
                'descripcion' => 'Alta humedad relativa',
                'factor_riesgo' => 3
            ],
            [
                'codigo' => 'TORMENTA',
                'nombre' => 'Tormenta Eléctrica',
                'descripcion' => 'Actividad eléctrica atmosférica',
                'factor_riesgo' => 7
            ],
        ];

        foreach ($condiciones as $condicion) {
            CondicionesClimatica::firstOrCreate(
                ['codigo' => $condicion['codigo']],
                [
                    'nombre' => $condicion['nombre'],
                    'descripcion' => $condicion['descripcion'],
                    'factor_riesgo' => $condicion['factor_riesgo']
                ]
            );
        }
    }
}
