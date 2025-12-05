<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadosSistema;

class EstadosSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            // Estados para usuarios
            [
                'tabla' => 'usuarios',
                'codigo' => 'ACTIVO',
                'nombre' => 'Activo',
                'descripcion' => 'Usuario activo en el sistema',
                'color' => '#28A745',
                'orden' => 1,
                'es_final' => null
            ],
            [
                'tabla' => 'usuarios',
                'codigo' => 'INACTIVO',
                'nombre' => 'Inactivo',
                'descripcion' => 'Usuario temporalmente inactivo',
                'color' => '#6C757D',
                'orden' => 2,
                'es_final' => null
            ],
            [
                'tabla' => 'usuarios',
                'codigo' => 'PENDIENTE',
                'nombre' => 'Pendiente',
                'descripcion' => 'Usuario pendiente de aprobación',
                'color' => '#FFC107',
                'orden' => 3,
                'es_final' => null
            ],
            [
                'tabla' => 'usuarios',
                'codigo' => 'ELIMINACION_SOLICITADA',
                'nombre' => 'Eliminación Solicitada',
                'descripcion' => 'Usuario solicitó eliminación de cuenta',
                'color' => '#DC3545',
                'orden' => 4,
                'es_final' => null
            ],
            // Estados para equipos
            [
                'tabla' => 'equipos',
                'codigo' => 'ACTIVO',
                'nombre' => 'Activo',
                'descripcion' => 'Equipo disponible',
                'color' => '#28A745',
                'orden' => 1,
                'es_final' => null
            ],
            [
                'tabla' => 'equipos',
                'codigo' => 'INACTIVO',
                'nombre' => 'Inactivo',
                'descripcion' => 'Equipo no disponible',
                'color' => '#6C757D',
                'orden' => 2,
                'es_final' => null
            ],
            [
                'tabla' => 'equipos',
                'codigo' => 'EN_MISION',
                'nombre' => 'En Misión',
                'descripcion' => 'Equipo desplegado en operación',
                'color' => '#007BFF',
                'orden' => 3,
                'es_final' => null
            ],
            // Estados para reportes
            [
                'tabla' => 'reportes',
                'codigo' => 'PENDIENTE',
                'nombre' => 'Pendiente',
                'descripcion' => 'Reporte recibido, sin atender',
                'color' => '#FFC107',
                'orden' => 1,
                'es_final' => false
            ],
            [
                'tabla' => 'reportes',
                'codigo' => 'EN_PROCESO',
                'nombre' => 'En Proceso',
                'descripcion' => 'Equipos trabajando en el incidente',
                'color' => '#007BFF',
                'orden' => 2,
                'es_final' => false
            ],
            [
                'tabla' => 'reportes',
                'codigo' => 'CONTROLADO',
                'nombre' => 'Controlado',
                'descripcion' => 'Incidente bajo control',
                'color' => '#17A2B8',
                'orden' => 3,
                'es_final' => false
            ],
            [
                'tabla' => 'reportes',
                'codigo' => 'EXTINGUIDO',
                'nombre' => 'Extinguido',
                'descripcion' => 'Incidente completamente resuelto',
                'color' => '#28A745',
                'orden' => 4,
                'es_final' => true
            ],
            [
                'tabla' => 'reportes',
                'codigo' => 'RECHAZADO',
                'nombre' => 'Rechazado',
                'descripcion' => 'Reporte rechazado',
                'color' => '#DC3545',
                'orden' => 5,
                'es_final' => true
            ],
            [
                'tabla' => 'reportes',
                'codigo' => 'FALSO_POSITIVO',
                'nombre' => 'Falso Positivo',
                'descripcion' => 'Reporte clasificado como falso positivo',
                'color' => '#35dcb2ff',
                'orden' => 6,
                'es_final' => true
            ],
            // Estados para recursos
            [
                'tabla' => 'recursos',
                'codigo' => 'PENDIENTE',
                'nombre' => 'Pendiente',
                'descripcion' => 'Pedido registrado',
                'color' => '#FFC107',
                'orden' => 1,
                'es_final' => false
            ],
            [
                'tabla' => 'recursos',
                'codigo' => 'APROBADO',
                'nombre' => 'Aprobado',
                'descripcion' => 'Pedido aprobado',
                'color' => '#28A745',
                'orden' => 2,
                'es_final' => false
            ],
            [
                'tabla' => 'recursos',
                'codigo' => 'RECHAZADO',
                'nombre' => 'Rechazado',
                'descripcion' => 'Pedido rechazado',
                'color' => '#DC3545',
                'orden' => 3,
                'es_final' => true
            ],
            [
                'tabla' => 'recursos',
                'codigo' => 'ENTREGADO',
                'nombre' => 'Entregado',
                'descripcion' => 'Recurso entregado',
                'color' => '#007BFF',
                'orden' => 4,
                'es_final' => true
            ],
        ];

        foreach ($estados as $estado) {
            EstadosSistema::firstOrCreate(
                [
                    'tabla' => $estado['tabla'],
                    'codigo' => $estado['codigo']
                ],
                [
                    'nombre' => $estado['nombre'],
                    'descripcion' => $estado['descripcion'],
                    'color' => $estado['color'],
                    'orden' => $estado['orden'],
                    'es_final' => $estado['es_final']
                ]
            );
        }
    }
}
