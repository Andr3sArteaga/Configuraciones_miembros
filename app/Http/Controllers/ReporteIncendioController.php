<?php

namespace App\Http\Controllers;

use App\Models\ReportesIncendio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReporteIncendioController extends Controller
{
    public function index()
    {
        $reportes_incendio = ReportesIncendio::all();
        return view(
            'reportes_incendio.index',
            compact('reportes_incendio')
        );
    }
    public function create()
    {
        return view('reportes_incendio.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_incidente' => 'required|string|max:255',
            'controlado' => 'nullable|boolean',
            'extension' => 'nullable|numeric',
            'condiciones_clima' => 'nullable|string|max:255',
            'equipos_en_uso' => 'nullable|string|max:255',
            'numero_bomberos' => 'nullable|integer',
            'necesita_mas_bomberos' => 'nullable|boolean',
            'apoyo_externo' => 'nullable|string|max:255',
            'comentario_adicional' => 'nullable|string',
            'fecha_creacion' => 'nullable|date',
            'id_usuario_creador' => 'nullable|string',
        ]);

        $validated['controlado'] = (bool) ($request->boolean('controlado'));
        $validated['necesita_mas_bomberos'] = (bool) ($request->boolean('necesita_mas_bomberos'));

        ReportesIncendio::create($validated);
        return redirect()->route('reportes_incendio.index')->with('success', 'Reporte de incendio creado');
    }
    public function edit(ReportesIncendio $reportes_incendio)
    {
        return view(
            'reportes_incendio.edit',
            compact('reportes_incendio')
        );
    }
    public function update(Request $request, ReportesIncendio $reportes_incendio)
    {
        $validated = $request->validate([
            'nombre_incidente' => 'required|string|max:255',
            'controlado' => 'nullable|boolean',
            'extension' => 'nullable|numeric',
            'condiciones_clima' => 'nullable|string|max:255',
            'equipos_en_uso' => 'nullable|string|max:255',
            'numero_bomberos' => 'nullable|integer',
            'necesita_mas_bomberos' => 'nullable|boolean',
            'apoyo_externo' => 'nullable|string|max:255',
            'comentario_adicional' => 'nullable|string',
            'fecha_creacion' => 'nullable|date',
            'id_usuario_creador' => 'nullable|string',
        ]);

        $validated['controlado'] = (bool) ($request->boolean('controlado'));
        $validated['necesita_mas_bomberos'] = (bool) ($request->boolean('necesita_mas_bomberos'));

        $reportes_incendio->update($validated);
        return redirect()->route('reportes_incendio.index');
    }
    public function show(ReportesIncendio $reportes_incendio)
    {
        return view('reportes_incendio.show', compact('reportes_incendio'));
    }

    public function destroy(ReportesIncendio $reportes_incendio)
    {
        $reportes_incendio->delete();
        return redirect()->route('reportes_incendio.index')->with('success', 'Reporte de incendio eliminado');
    }
}
