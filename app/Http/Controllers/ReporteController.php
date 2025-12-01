<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\TiposIncidente;
use App\Models\NivelesGravedad;
use App\Models\EstadosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Reportes ciudadanos no están vinculados a usuarios específicos
        // Todos los usuarios autenticados pueden ver todos los reportes
        $reportes = Reporte::orderBy('fecha_hora', 'desc')
            ->paginate(20);

        // Cargar manualmente todas las relaciones para evitar problemas con UUID en whereIn
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        // Asociar relaciones a cada reporte
        foreach ($reportes as $reporte) {
            if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
                $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
            }
            if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
            }
            if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
            }
        }

        return view('reportes.index', compact('reportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();

        return view('reportes.create', compact('tiposIncidente', 'nivelesGravedad'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer|min:0',
            'cant_paramedicos' => 'nullable|integer|min:0',
            'cant_veterinarios' => 'nullable|integer|min:0',
            'cant_autoridades' => 'nullable|integer|min:0',
        ]);

        // Obtener estado "pendiente" para reportes
        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')
            ->where('codigo', 'pendiente')
            ->first();

        // Construir el punto geográfico si hay coordenadas
        $ubicacion = null;
        if ($request->latitud && $request->longitud) {
            $ubicacion = "POINT({$request->longitud} {$request->latitud})";
        }

        Reporte::create([
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => $request->fecha_hora,
            'nombre_lugar' => $request->nombre_lugar,
            'ubicacion' => $ubicacion ? DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')") : null,
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => $request->cant_bomberos ?? 0,
            'cant_paramedicos' => $request->cant_paramedicos ?? 0,
            'cant_veterinarios' => $request->cant_veterinarios ?? 0,
            'cant_autoridades' => $request->cant_autoridades ?? 0,
            'estado_id' => $estadoPendiente->id ?? null,
        ]);

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reporte = Reporte::findOrFail($id);

        // Cargar relaciones manualmente para evitar problema de UUID con whereIn
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
            $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
        }
        if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
            $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
        }
        if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
            $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
        }

        return view('reportes.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reporte = Reporte::findOrFail($id);
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();
        $estados = EstadosSistema::where('tabla', 'reportes')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('reportes.edit', compact('reporte', 'tiposIncidente', 'nivelesGravedad', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reporte = Reporte::findOrFail($id);

        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer|min:0',
            'cant_paramedicos' => 'nullable|integer|min:0',
            'cant_veterinarios' => 'nullable|integer|min:0',
            'cant_autoridades' => 'nullable|integer|min:0',
            'estado_id' => 'nullable|uuid|exists:estados_sistema,id',
        ]);

        // Construir el punto geográfico si hay coordenadas
        $ubicacion = null;
        if ($request->latitud && $request->longitud) {
            $ubicacion = "POINT({$request->longitud} {$request->latitud})";
        }

        $data = [
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => $request->fecha_hora,
            'nombre_lugar' => $request->nombre_lugar,
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => $request->cant_bomberos ?? 0,
            'cant_paramedicos' => $request->cant_paramedicos ?? 0,
            'cant_veterinarios' => $request->cant_veterinarios ?? 0,
            'cant_autoridades' => $request->cant_autoridades ?? 0,
            'estado_id' => $request->estado_id,
        ];

        if ($ubicacion) {
            DB::table('reportes')
                ->where('id', $id)
                ->update(array_merge($data, [
                    'ubicacion' => DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')")
                ]));
        } else {
            $reporte->update($data);
        }

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            $reporte->delete();

            return redirect()->route('reportes.index')
                ->with('success', 'Reporte eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('reportes.index')
                ->with('error', 'Error al eliminar el reporte.');
        }
    }

    /**
     * Mostrar formulario público de reporte (para invitados/usuarios no autenticados)
     */
    public function formularioPublico()
    {
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();

        return view('reportes.publico', compact('tiposIncidente', 'nivelesGravedad'));
    }

    /**
     * Guardar reporte público (sin autenticación)
     */
    public function storePublico(Request $request)
    {
        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer|min:0',
            'cant_paramedicos' => 'nullable|integer|min:0',
            'cant_veterinarios' => 'nullable|integer|min:0',
            'cant_autoridades' => 'nullable|integer|min:0',
        ]);

        // Obtener estado "pendiente" para reportes
        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')
            ->where('codigo', 'pendiente')
            ->first();

        // Construir el punto geográfico si hay coordenadas
        $ubicacion = null;
        if ($request->latitud && $request->longitud) {
            $ubicacion = "POINT({$request->longitud} {$request->latitud})";
        }

        Reporte::create([
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => $request->fecha_hora,
            'nombre_lugar' => $request->nombre_lugar,
            'ubicacion' => $ubicacion ? DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')") : null,
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => $request->cant_bomberos ?? 0,
            'cant_paramedicos' => $request->cant_paramedicos ?? 0,
            'cant_veterinarios' => $request->cant_veterinarios ?? 0,
            'cant_autoridades' => $request->cant_autoridades ?? 0,
            'estado_id' => $estadoPendiente->id ?? null,
        ]);

        return redirect()->route('reporte.publico')
            ->with('success', 'Reporte enviado exitosamente. Gracias por tu colaboración.');
    }
}
