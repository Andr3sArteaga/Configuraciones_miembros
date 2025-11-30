<?php

namespace App\Http\Controllers;

use App\Models\ReportesIncendio;
use App\Models\CondicionesClimatica;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReporteIncendioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportes = ReportesIncendio::with(['usuario', 'condiciones_climatica'])
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(20);
        return view('reportes-incendio.index', compact('reportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $condicionesClimaticas = CondicionesClimatica::where('activo', true)
            ->orderBy('nombre')
            ->get();
        
        return view('reportes-incendio.create', compact('condicionesClimaticas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_incidente' => 'required|string|max:255',
            'condicion_climatica_id' => 'nullable|exists:condiciones_climaticas,id',
            'extension' => 'nullable|numeric|min:0',
            'numero_bomberos' => 'nullable|integer|min:0',
            'necesita_mas_bomberos' => 'nullable|boolean',
            'apoyo_externo' => 'nullable|string',
            'comentario_adicional' => 'nullable|string',
            'equipos_en_uso' => 'nullable|string',
            'controlado' => 'nullable|boolean',
        ]);

        // Generar UUID para el reporte
        $validated['id'] = Str::uuid()->toString();
        
        // Establecer fecha de creación
        $validated['fecha_creacion'] = now();
        
        // Asignar usuario creador
        $validated['id_usuario_creador'] = Auth::id();
        
        // Convertir necesita_mas_bomberos a booleano
        $validated['necesita_mas_bomberos'] = $request->has('necesita_mas_bomberos');
        
        // Convertir controlado a booleano
        $validated['controlado'] = $request->has('controlado');

        ReportesIncendio::create($validated);

        return redirect()->route('reportes-incendio.index')
            ->with('success', 'Reporte de incendio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reporte = ReportesIncendio::with(['usuario', 'condiciones_climatica'])
            ->findOrFail($id);
        
        return view('reportes-incendio.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reporte = ReportesIncendio::findOrFail($id);
        
        $condicionesClimaticas = CondicionesClimatica::where('activo', true)
            ->orderBy('nombre')
            ->get();
        
        return view('reportes-incendio.edit', compact('reporte', 'condicionesClimaticas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reporte = ReportesIncendio::findOrFail($id);
        
        $validated = $request->validate([
            'nombre_incidente' => 'required|string|max:255',
            'condicion_climatica_id' => 'nullable|exists:condiciones_climaticas,id',
            'extension' => 'nullable|numeric|min:0',
            'numero_bomberos' => 'nullable|integer|min:0',
            'necesita_mas_bomberos' => 'nullable|boolean',
            'apoyo_externo' => 'nullable|string',
            'comentario_adicional' => 'nullable|string',
            'equipos_en_uso' => 'nullable|string',
            'controlado' => 'nullable|boolean',
        ]);
        
        // Convertir necesita_mas_bomberos a booleano
        $validated['necesita_mas_bomberos'] = $request->has('necesita_mas_bomberos');
        
        // Convertir controlado a booleano
        $validated['controlado'] = $request->has('controlado');
        
        $reporte->update($validated);
        
        return redirect()->route('reportes-incendio.index')
            ->with('success', 'Reporte de incendio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reporte = ReportesIncendio::findOrFail($id);
        $reporte->delete();
        
        return redirect()->route('reportes-incendio.index')
            ->with('success', 'Reporte de incendio eliminado exitosamente.');
    }
}
