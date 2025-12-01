<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genero;

class GenerosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            ['codigo' => 'MASCULINO', 'descripcion' => 'Masculino'],
            ['codigo' => 'FEMENINO', 'descripcion' => 'Femenino'],
            ['codigo' => 'OTRO', 'descripcion' => 'Otro'],
            ['codigo' => 'PREFIERO_NO_DECIR', 'descripcion' => 'Prefiero no decir'],
        ];

        foreach ($generos as $genero) {
            Genero::firstOrCreate(
                ['codigo' => $genero['codigo']],
                ['descripcion' => $genero['descripcion']]
            );
        }
    }
}
