<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index()
    {
        $recursos = Recurso::all();
        return view('recursos.index', compact('recursos'));
    }

    public function create()
    {
        return view('recursos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
            'fecha_pedido' => 'nullable|date',
            'estado_del_pedido' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'equipoid' => 'nullable|string',
        ]);

        Recurso::create($validated);
        return redirect()->route('recursos.index')->with('success', 'Recurso creado correctamente');
    }

    public function edit(Recurso $recurso)
    {
        return view('recursos.edit', compact('recurso'));
    }

    public function update(Request $request, Recurso $recurso)
    {
        $validated = $request->validate([
            'codigo' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
            'fecha_pedido' => 'nullable|date',
            'estado_del_pedido' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'equipoid' => 'nullable|string',
        ]);

        $recurso->update($validated);
        return redirect()->route('recursos.index')->with('success', 'Recurso actualizado');
    }

    public function destroy(Recurso $recurso)
    {
        $recurso->delete();
        return redirect()->route('recursos.index')->with('success', 'Recurso eliminado');
    }

    public function show(Recurso $recurso)
    {
        return view('recursos.show', compact('recurso'));
    }
}
