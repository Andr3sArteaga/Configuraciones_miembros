<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\FocosCalor;
use App\Models\Reporte;
use Illuminate\Http\Request;

class FocoCalorController extends Controller
{
    public function index()
    {
        $focos = FocosCalor::orderBy('acq_date', 'desc')->paginate(20);
        $countEquiposDesplegados = Equipo::whereNotNull('ubicacion')
            ->where('ubicacion', '!=', '')
            ->count();

        // Obtener reportes con ubicación para mostrar en el mapa
        $reportes = Reporte::with(['tipos_incidente', 'niveles_gravedad', 'estados_sistema'])
            ->whereNotNull('ubicacion')
            ->orderBy('fecha_hora', 'desc')
            ->limit(100)
            ->get();

        return view('focos-calor.index', compact('focos', 'countEquiposDesplegados', 'reportes'));
    }

    public function mapa()
    {
        $focos = FocosCalor::orderBy('acq_date', 'desc')->limit(100)->get();
        return view('focos-calor.mapa', compact('focos'));
    }

    public function api()
    {
        $focos = FocosCalor::orderBy('acq_date', 'desc')->limit(100)->get();
        return response()->json($focos);
    }
}
