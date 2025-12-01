<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposSangre;

class TiposSangreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposSangre = [
            ['codigo' => 'A+', 'descripcion' => 'A Positivo'],
            ['codigo' => 'A-', 'descripcion' => 'A Negativo'],
            ['codigo' => 'B+', 'descripcion' => 'B Positivo'],
            ['codigo' => 'B-', 'descripcion' => 'B Negativo'],
            ['codigo' => 'AB+', 'descripcion' => 'AB Positivo'],
            ['codigo' => 'AB-', 'descripcion' => 'AB Negativo'],
            ['codigo' => 'O+', 'descripcion' => 'O Positivo'],
            ['codigo' => 'O-', 'descripcion' => 'O Negativo'],
        ];

        foreach ($tiposSangre as $tipo) {
            TiposSangre::firstOrCreate(
                ['codigo' => $tipo['codigo']],
                ['descripcion' => $tipo['descripcion']]
            );
        }
    }
}
