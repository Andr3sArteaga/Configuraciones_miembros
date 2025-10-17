<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\ReportesIncendio;
use App\Models\Recurso;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $nameUser = Auth::user()->name;
        $totalReportes = Reporte::count();
        $totalIncendios = ReportesIncendio::count();
        $totalRecursos = Recurso::count();
        $totalUsuarios = Usuario::count();

        $ultimosReportes = Reporte::orderByDesc('fecha_hora')
            ->limit(5)
            ->get(['id', 'nombre_reportante', 'fecha_hora', 'estado', 'nombre_lugar']);

        $ultimosIncendios = ReportesIncendio::orderByDesc('fecha_creacion')
            ->limit(5)
            ->get(['id', 'nombre_incidente', 'controlado', 'fecha_creacion', 'numero_bomberos']);

        $ultimosRecursos = Recurso::orderByDesc('creado')
            ->limit(5)
            ->get(['id', 'codigo', 'descripcion', 'estado_del_pedido', 'creado']);

        // Datasets para gráficos
        $desde = Carbon::now()->subDays(14)->startOfDay();
        $reportesRaw = Reporte::whereNotNull('fecha_hora')
            ->where('fecha_hora', '>=', $desde)
            ->selectRaw('DATE(fecha_hora) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Normalizar últimos 15 días para que las fechas sin datos aparezcan con 0
        $labelsReportes = [];
        $dataReportes = [];
        for ($i = 14; $i >= 0; $i--) {
            $dia = Carbon::now()->subDays($i)->toDateString();
            $labelsReportes[] = Carbon::now()->subDays($i)->format('d/m');
            $match = $reportesRaw->firstWhere('fecha', $dia);
            $dataReportes[] = $match ? (int)$match->total : 0;
        }

        $incendiosControladosRaw = ReportesIncendio::selectRaw('controlado, COUNT(*) as total')
            ->groupBy('controlado')
            ->pluck('total', 'controlado');
        $incendiosControlados = [
            'labels' => ['No controlado', 'Controlado'],
            'data' => [
                (int)($incendiosControladosRaw[0] ?? 0),
                (int)($incendiosControladosRaw[1] ?? 0),
            ],
        ];

        $recursosPorEstadoRaw = Recurso::selectRaw("COALESCE(estado_del_pedido, 'N/D') as estado, COUNT(*) as total")
            ->groupByRaw("COALESCE(estado_del_pedido, 'N/D')")
            ->orderByDesc('total')
            ->limit(6)
            ->get();
        $recursosPorEstado = [
            'labels' => $recursosPorEstadoRaw->pluck('estado')->toArray(),
            'data' => $recursosPorEstadoRaw->pluck('total')->map(fn($v) => (int)$v)->toArray(),
        ];

        return view('home', compact(
            'nameUser',
            'totalReportes',
            'totalIncendios',
            'totalRecursos',
            'totalUsuarios',
            'ultimosReportes',
            'ultimosIncendios',
            'ultimosRecursos',
            'labelsReportes',
            'dataReportes',
            'incendiosControlados',
            'recursosPorEstado'
        ));
    }
}
