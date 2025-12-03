<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reporte;
use App\Models\TiposIncidente;
use App\Models\NivelesGravedad;
use App\Models\EstadosSistema;
use Illuminate\Support\Facades\DB;

class ReportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las tablas relacionadas
        $tipoForestal = TiposIncidente::where('codigo', 'FORESTAL')->first();
        $tipoEstructural = TiposIncidente::where('codigo', 'ESTRUCTURAL')->first();
        $tipoVehicular = TiposIncidente::where('codigo', 'VEHICULAR')->first();
        $tipoIndustrial = TiposIncidente::where('codigo', 'INDUSTRIAL')->first();
        $tipoOtro = TiposIncidente::where('codigo', 'OTRO')->first();

        $gravedadLeve = NivelesGravedad::where('codigo', 'LEVE')->first();
        $gravedadModerado = NivelesGravedad::where('codigo', 'MODERADO')->first();
        $gravedadGrave = NivelesGravedad::where('codigo', 'GRAVE')->first();
        $gravedadCritico = NivelesGravedad::where('codigo', 'CRITICO')->first();

        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')->where('codigo', 'PENDIENTE')->first();
        $estadoProceso = EstadosSistema::where('tabla', 'reportes')->where('codigo', 'EN_PROCESO')->first();
        $estadoControlado = EstadosSistema::where('tabla', 'reportes')->where('codigo', 'CONTROLADO')->first();
        $estadoExtinguido = EstadosSistema::where('tabla', 'reportes')->where('codigo', 'EXTINGUIDO')->first();

        $reportes = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
                'nombre_reportante' => 'Juan Pérez Rodríguez',
                'telefono_contacto' => '+591 71234567',
                'fecha_hora' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Parque Nacional Noel Kempff',
                'ubicacion' => ['lat' => -14.5667, 'lng' => -60.9667],
                'tipo_incidente_id' => $tipoForestal?->id,
                'gravedad_id' => $gravedadGrave?->id,
                'comentario_adicional' => 'Incendio forestal de gran magnitud en zona protegida. Se requiere intervención urgente.',
                'cant_bomberos' => 8,
                'cant_paramedicos' => 2,
                'cant_veterinarios' => 1,
                'cant_autoridades' => 3,
                'estado_id' => $estadoProceso?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440002',
                'nombre_reportante' => 'María López Silva',
                'telefono_contacto' => '+591 72345678',
                'fecha_hora' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Centro Comercial Las Américas',
                'ubicacion' => ['lat' => -17.7833, 'lng' => -63.1821],
                'tipo_incidente_id' => $tipoEstructural?->id,
                'gravedad_id' => $gravedadCritico?->id,
                'comentario_adicional' => 'Incendio en piso 3 del centro comercial. Evacuación en curso. Posibles personas atrapadas.',
                'cant_bomberos' => 15,
                'cant_paramedicos' => 5,
                'cant_veterinarios' => 0,
                'cant_autoridades' => 8,
                'estado_id' => $estadoExtinguido?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440003',
                'nombre_reportante' => 'Carlos Mendoza',
                'telefono_contacto' => '+591 73456789',
                'fecha_hora' => now()->subHours(3)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Carretera Santa Cruz - Cochabamba Km 45',
                'ubicacion' => ['lat' => -17.6500, 'lng' => -63.3000],
                'tipo_incidente_id' => $tipoVehicular?->id,
                'gravedad_id' => $gravedadModerado?->id,
                'comentario_adicional' => 'Camión cisterna en llamas en carretera principal. Tráfico desviado.',
                'cant_bomberos' => 6,
                'cant_paramedicos' => 2,
                'cant_veterinarios' => 0,
                'cant_autoridades' => 4,
                'estado_id' => $estadoControlado?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440004',
                'nombre_reportante' => 'Ana Gutiérrez',
                'telefono_contacto' => '+591 74567890',
                'fecha_hora' => now()->subMinutes(30)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Zona Industrial Norte - Planta Textil',
                'ubicacion' => ['lat' => -17.7500, 'lng' => -63.2000],
                'tipo_incidente_id' => $tipoIndustrial?->id,
                'gravedad_id' => $gravedadGrave?->id,
                'comentario_adicional' => 'Incendio en almacén de materiales inflamables. Riesgo de explosión.',
                'cant_bomberos' => 12,
                'cant_paramedicos' => 3,
                'cant_veterinarios' => 0,
                'cant_autoridades' => 5,
                'estado_id' => $estadoPendiente?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440005',
                'nombre_reportante' => 'Roberto Sánchez',
                'telefono_contacto' => '+591 75678901',
                'fecha_hora' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Comunidad Chiquitana San Rafael',
                'ubicacion' => ['lat' => -16.2500, 'lng' => -61.4167],
                'tipo_incidente_id' => $tipoForestal?->id,
                'gravedad_id' => $gravedadLeve?->id,
                'comentario_adicional' => 'Quema controlada que se salió de control en área agrícola pequeña.',
                'cant_bomberos' => 4,
                'cant_paramedicos' => 1,
                'cant_veterinarios' => 1,
                'cant_autoridades' => 2,
                'estado_id' => $estadoExtinguido?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440006',
                'nombre_reportante' => 'Patricia Rojas',
                'telefono_contacto' => '+591 76789012',
                'fecha_hora' => now()->subHours(12)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Barrio El Carmen - Casa Habitación',
                'ubicacion' => ['lat' => -17.8000, 'lng' => -63.1700],
                'tipo_incidente_id' => $tipoEstructural?->id,
                'gravedad_id' => $gravedadModerado?->id,
                'comentario_adicional' => 'Incendio en cocina por cortocircuito. Familia evacuada sin heridos.',
                'cant_bomberos' => 5,
                'cant_paramedicos' => 2,
                'cant_veterinarios' => 0,
                'cant_autoridades' => 2,
                'estado_id' => $estadoControlado?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440007',
                'nombre_reportante' => 'Luis Fernández',
                'telefono_contacto' => '+591 77890123',
                'fecha_hora' => now()->subDays(7)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Reserva Municipal Lomas de Arena',
                'ubicacion' => ['lat' => -17.9167, 'lng' => -63.0333],
                'tipo_incidente_id' => $tipoForestal?->id,
                'gravedad_id' => $gravedadCritico?->id,
                'comentario_adicional' => 'Incendio forestal masivo afectando área protegida. Fauna en riesgo.',
                'cant_bomberos' => 20,
                'cant_paramedicos' => 4,
                'cant_veterinarios' => 3,
                'cant_autoridades' => 10,
                'estado_id' => $estadoExtinguido?->id,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440008',
                'nombre_reportante' => 'Sofía Martínez',
                'telefono_contacto' => '+591 78901234',
                'fecha_hora' => now()->subHours(6)->format('Y-m-d H:i:s'),
                'nombre_lugar' => 'Taller Mecánico El Pino',
                'ubicacion' => ['lat' => -17.7700, 'lng' => -63.1900],
                'tipo_incidente_id' => $tipoOtro?->id,
                'gravedad_id' => $gravedadLeve?->id,
                'comentario_adicional' => 'Pequeño incendio en taller por derrame de combustible. Controlado rápidamente.',
                'cant_bomberos' => 3,
                'cant_paramedicos' => 1,
                'cant_veterinarios' => 0,
                'cant_autoridades' => 1,
                'estado_id' => $estadoExtinguido?->id,
            ],
        ];

        foreach ($reportes as $reporteData) {
            // Verificar si el reporte ya existe por ID
            $existe = Reporte::where('id', $reporteData['id'])->exists();

            if (!$existe) {
                // Convertir ubicación a formato PostGIS
                if (isset($reporteData['ubicacion'])) {
                    $lat = $reporteData['ubicacion']['lat'];
                    $lng = $reporteData['ubicacion']['lng'];
                    $reporteData['ubicacion'] = DB::raw("ST_SetSRID(ST_MakePoint({$lng}, {$lat}), 4326)");
                }

                Reporte::create($reporteData);
                $this->command->info("✅ Reporte creado: {$reporteData['nombre_lugar']}");
            } else {
                $this->command->warn("⚠️  Reporte ya existe: {$reporteData['nombre_lugar']} (ID: {$reporteData['id']})");
            }
        }

        $this->command->info('🎉 Seeders de reportes completados exitosamente.');
    }
}
