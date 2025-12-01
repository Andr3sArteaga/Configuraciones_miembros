<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportesIncendio;
use App\Models\Reporte;
use App\Models\Equipo;
use App\Models\Recurso;
use App\Models\FocosCalor;
use App\Models\NoticiasIncendio;
use App\Models\EstadosSistema;
use App\Models\TiposIncidente;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Estadísticas para InfoBoxes
        // Contar reportes con gravedad "Activo"
        $gravedadActivo = \App\Models\NivelesGravedad::where('codigo', 'activo')
            ->orWhere('nombre', 'Activo')
            ->first();
        $incendiosActivos = Reporte::where('gravedad_id', $gravedadActivo?->id)->count();

        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')
            ->where('codigo', 'pendiente')
            ->first();
        $reportesPendientes = Reporte::where('estado_id', $estadoPendiente?->id)->count();

        $estadoDesplegado = EstadosSistema::where('tabla', 'equipos')
            ->where('codigo', 'desplegado')
            ->first();
        $equiposDesplegados  = Equipo::whereNotNull('reporte_id')
            ->count();

        $estadoSolicitado = EstadosSistema::where('tabla', 'recursos')
            ->where('codigo', 'solicitado')
            ->first();
        $recursosSolicitados = Recurso::where('estado_id', $estadoSolicitado?->id)->count();

        // Datos para el mapa
        $focos = FocosCalor::whereDate('acq_date', today())
            ->orderBy('acq_date', 'desc')
            ->orderBy('acq_time', 'desc')
            ->limit(100)
            ->get();
        $focosCalor = $focos->count();

        // Reportes recientes
        $reportesRecientes = Reporte::orderBy('fecha_hora', 'desc')
            ->limit(5)
            ->get();

        // Reportes disponibles (con ubicación y estado pendiente)
        $reportesDisponibles = Reporte::whereNotNull('ubicacion')
            ->where('ubicacion', '!=', '')
            ->when($estadoPendiente, function ($query) use ($estadoPendiente) {
                return $query->where('estado_id', $estadoPendiente->id);
            })
            ->orderBy('fecha_hora', 'desc')
            ->limit(100)
            ->get();

        // Cargar relaciones manualmente para evitar problema de UUID con whereIn
        $estadosSistema = EstadosSistema::all()->keyBy('id');
        foreach ($reportesRecientes as $reporte) {
            if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
            }
        }

        $reportesHoy = Reporte::whereDate('fecha_hora', today())->count();

        // Incendios controlados
        $incendiosControlados = ReportesIncendio::where('controlado', true)->count();

        // Bomberos activos (usuarios con rol de bombero y estado activo)
        $bomberosActivos = \App\Models\Usuario::whereHas('role', function ($query) {
            $query->where('codigo', 'BOMBERO');
        })->whereHas('estados_sistema', function ($query) {
            $query->where('codigo', 'ACTIVO');
        })->count();

        // Noticias recientes
        $noticiasRecientes = NoticiasIncendio::orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Equipos con relaciones (solo para usuarios autenticados)
        $equipos = collect([]);
        if (auth()->check()) {
            $equipos = Equipo::with('reporte')->orderBy('creado', 'desc')
                ->limit(10)
                ->get();

            // Cargar relación estados_sistema manualmente
            foreach ($equipos as $equipo) {
                if ($equipo->estado_id && isset($estadosSistema[$equipo->estado_id])) {
                    $equipo->setRelation('estados_sistema', $estadosSistema[$equipo->estado_id]);
                }
            }
        }

        // Datos para gráfico de incendios por mes
        $mesesLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $incendiosPorMesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $incendiosPorMesData[] = ReportesIncendio::whereYear('fecha_creacion', date('Y'))
                ->whereMonth('fecha_creacion', $i)
                ->count();
        }

        // Datos para gráfico de tipos de incidente
        $tiposIncidente = TiposIncidente::withCount('reportes')
            ->get()
            ->filter(function ($tipo) {
                return $tipo->reportes_count > 0;
            });

        $tiposLabels = $tiposIncidente->pluck('nombre')->toArray();
        $tiposData = $tiposIncidente->pluck('reportes_count')->toArray();

        // Si no hay datos, usar datos de ejemplo
        if (empty($tiposLabels)) {
            $tiposLabels = ['Incendio Forestal', 'Incendio Urbano', 'Rescate', 'Otro'];
            $tiposData = [0, 0, 0, 0];
        }

        return view('home', compact(
            'incendiosActivos',
            'reportesPendientes',
            'equiposDesplegados',
            'recursosSolicitados',
            'focos',
            'focosCalor',
            'reportesRecientes',
            'reportesHoy',
            'incendiosControlados',
            'bomberosActivos',
            'noticiasRecientes',
            'equipos',
            'reportesDisponibles',
            'mesesLabels',
            'incendiosPorMesData',
            'tiposLabels',
            'tiposData'
        ));
    }
}
