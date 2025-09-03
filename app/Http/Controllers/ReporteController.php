<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReporteController extends Controller
{
    public function index()
    {
        $reportes = Reporte::all();
        return view('reportes.index', compact('reportes'));
    }

    public function create()
    {
        return view('reportes.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_reportante' => 'required|string|max:255',
            'telefono_contacto' => 'nullable|string|max:255',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:255',
            'tipo_incendio' => 'nullable|string|max:255',
            'gravedad_incendio' => 'nullable|string|max:255',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer',
            'cant_paramedicos' => 'nullable|integer',
            'cant_veterinarios' => 'nullable|integer',
            'cant_autoridades' => 'nullable|integer',
            'estado' => 'nullable|string|max:255',
        ]);

        Reporte::create($validated);

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte creado exitosamente.');
    }
    public function edit(Reporte $reporte)
    {
        return view('reportes.edit', compact('reporte'));
    }

    public function update(Request $request, Reporte $reporte)
    {
        $validated = $request->validate([
            'nombre_reportante' => 'required|string|max:255',
            'telefono_contacto' => 'nullable|string|max:255',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:255',
            'tipo_incendio' => 'nullable|string|max:255',
            'gravedad_incendio' => 'nullable|string|max:255',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer',
            'cant_paramedicos' => 'nullable|integer',
            'cant_veterinarios' => 'nullable|integer',
            'cant_autoridades' => 'nullable|integer',
            'estado' => 'nullable|string|max:255',
        ]);

        $reporte->update($validated);

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado exitosamente.');
    }

    public function show(Reporte $reporte)
    {
        return view('reportes.show', compact('reporte'));
    }

    public function destroy(Reporte $reporte)
    {
        $reporte->delete();
        return redirect()->route('reportes.index')->with('success', 'Reporte eliminado exitosamente.');
    }
}
