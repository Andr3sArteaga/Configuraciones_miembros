<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportesIncendio;
use App\Models\Usuario;
use App\Models\Role;
use App\Models\CondicionesClimatica;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ReportesIncendioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. GARANTIZAR USUARIOS BOMBEROS
        $rolBombero = Role::where('codigo', 'BOMBERO')->first();
        if (!$rolBombero) {
            $rolBombero = Role::where('nombre', 'like', '%Bombero%')->first();
        }

        $bomberos = [];
        $nombresBomberos = [
            ['Juan', 'Perez', '70000010'],
            ['Maria', 'Gonzales', '70000011'],
            ['Carlos', 'Mamani', '70000012'],
            ['Ana', 'Vargas', '70000013']
        ];

        foreach ($nombresBomberos as $idx => $datos) {
            $email = strtolower($datos[0]) . '.' . strtolower($datos[1]) . '@alaschiquitanas.com';
            $bombero = Usuario::where('email', $email)->first();

            if (!$bombero && $rolBombero) {
                $bombero = Usuario::create([
                    'id' => Str::uuid(),
                    'nombre' => $datos[0],
                    'apellido' => $datos[1],
                    'email' => $email,
                    'password' => Hash::make('12345678'),
                    'telefono' => $datos[2],
                    'ci' => 'CI-' . rand(10000, 99999),
                    'fecha_nacimiento' => '1990-01-01',
                    'rol_id' => $rolBombero->id,
                    'entidad_perteneciente' => 'Estación de Bomberos ' . ($idx + 1),
                    'creado' => now(),
                ]);
            }
            if ($bombero) $bomberos[] = $bombero;
        }
        
        if (empty($bomberos)) {
            $admin = Usuario::first(); 
            if ($admin) $bomberos[] = $admin;
        }

        // 2. DATOS AUXILIARES
        $climas = CondicionesClimatica::all();
        $comentarios = [
            'Fuego avanza rápido por el viento fuerte.',
            'Zona de difícil acceso, se requiere apoyo aéreo.',
            'Incendio confinado gracias a cortafuegos naturales.',
            'Riesgo para viviendas cercanas, evacuación preventiva.',
            'Se sospecha origen intencional.',
            'Controlado en un 80%, personal exhausto.',
            'Reinicio de foco por cambio de viento.',
            'Operativo exitoso, sin novedades.',
            'Requiere monitoreo constante por clima seco.',
            'Afectación menor a flora local.'
        ];


        // 3. GENERAR MUCHOS REPORTES (PROCEDURAL PARA VOLUMEN Y REALISMO)
        $year = now()->year;
        
        $lugares = [
            'Parque Noel Kempff', 'Reserva Tucavaca', 'Lomas de Arena', 'Serranía Chiquitana', 
            'Bajo Paraguá', 'Otuquis', 'San Matías', 'Copaibo', 'Valle de Tucabaca', 
            'Laguna Concepción', 'Kaa-Iya', 'Río Blanco y Negro', 'San Ignacio de Velasco',
            'San Julián', 'Roboré', 'Puerto Suárez', 'San José de Chiquitos', 'Concepción',
            'Ascensión de Guarayos', 'El Torno', 'Cotoca', 'Warnes', 'Montero', 'Pailón',
            'Cuatro Cañadas', 'San Javier', 'Urubichá', 'Charagua', 'Camiri', 'Vallegrande'
        ];

        $tipos = ['Incendio Forestal', 'Quema de Pastizales', 'Foco de Calor', 'Quema Agrícola', 'Incendio de Interfaz'];
        
        // Cantidad de reportes a generar (bastantes para llenar el gráfico)
        $totalReportes = 100;

        $this->command->info("🔥 Generando {$totalReportes} reportes distribuidos en el año...");

        for ($i = 0; $i < $totalReportes; $i++) {
            // Selección aleatoria de datos base
            $lugar = $lugares[array_rand($lugares)];
            $tipo = $tipos[array_rand($tipos)];
            $nombreIncidente = "{$tipo} - {$lugar}";
            
            // Lógica de FECHAS con peso estacional (Más incendios en Ago-Sep-Oct)
            // Probabilidades por trimestre aproximadas:
            // Ene-Mar: 10% | Abr-Jun: 15% | Jul-Sep: 50% | Oct-Dic: 25%
            $rand = rand(1, 100);
            if ($rand <= 10) { $mes = rand(1, 3); }
            elseif ($rand <= 25) { $mes = rand(4, 6); }
            elseif ($rand <= 75) { $mes = rand(7, 9); } // Temporada alta
            else { $mes = rand(10, 12); }

            // Fecha específica
            try {
                $fechaBase = Carbon::create($year, $mes, 1, 0, 0, 0);
                if ($fechaBase->isFuture()) $fechaBase = $fechaBase->subYear(); // Ajuste si es futuro
                $fechaCreacion = $fechaBase->copy()->addDays(rand(0, 27))->addHours(rand(0, 23));
            } catch (\Exception $e) {
                $fechaCreacion = now();
            }

            // Datos técnicos variables
            $esForestal = str_contains($tipo, 'Forestal') || str_contains($tipo, 'Quema');
            $extension = $esForestal ? rand(10, 5000) + (rand(0, 99) / 100) : rand(0, 5) + (rand(0, 99) / 100);
            $controlado = (rand(1, 100) > 40); // 60% probabilidad de estar controlado
            $numBomberos = $esForestal ? rand(10, 80) : rand(3, 15);
            
            $usuario = $bomberos[array_rand($bomberos)];
            $clima = $climas->isNotEmpty() ? $climas->random() : null;
            $comentario = $comentarios[array_rand($comentarios)];

            // ID único
            $uuidSemilla = md5($nombreIncidente . $fechaCreacion->timestamp . $i);
            $uuid = substr($uuidSemilla, 0, 8) . '-9999-8888-7777-' . substr($uuidSemilla, -12);

            if (ReportesIncendio::where('id', $uuid)->exists()) {
                continue;
            }

            ReportesIncendio::create([
                'id' => $uuid,
                'nombre_incidente' => $nombreIncidente,
                'controlado' => $controlado,
                'extension' => $extension,
                'condicion_climatica_id' => $clima?->id,
                'equipos_en_uso' => $esForestal ? 'Cisternas, Maquinaria Pesada, Herramientas' : 'Mochilas, Batefuegos',
                'numero_bomberos' => $numBomberos,
                'necesita_mas_bomberos' => !$controlado && $esForestal,
                'apoyo_externo' => ($extension > 1000) ? 'Fuerza Aérea' : null,
                'comentario_adicional' => $comentario,
                'fecha_creacion' => $fechaCreacion,
                'id_usuario_creador' => $usuario->id,
            ]);
        }


        $this->command->info('🎉 Seeder ReportesIncendio (Anual) completado.');
    }
}
