<?php

namespace App\Http\Controllers;

use App\Models\ComunariosApoyo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComunarioApoyoController extends Controller
{
    public function index()
    {
        $comunariosApoyo = ComunariosApoyo::all();
        return view('comunarios_apoyo.index', compact('comunariosApoyo'));
    }

    public function create()
    {
        return view('comunarios_apoyo.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'entidad_perteneciente' => 'required|string|max:255',
        ]);

        ComunariosApoyo::create($validated);
        return redirect()->route('comunarios_apoyo.index');
    }

    public function edit(ComunariosApoyo $comunarios_apoyo)
    {
        return view('comunarios_apoyo.edit', ['comunario' => $comunarios_apoyo]);
    }

    public function update(Request $request, ComunariosApoyo $comunarios_apoyo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'entidad_perteneciente' => 'required|string|max:255',
        ]);

        $comunarios_apoyo->update($validated);
        return redirect()->route('comunarios_apoyo.index');
    }

    public function destroy(ComunariosApoyo $comunarios_apoyo)
    {
        $comunarios_apoyo->delete();
        return redirect()->route('comunarios_apoyo.index')
            ->with('success', 'Comunario de apoyo eliminado exitosamente.');
    }

    public function show(ComunariosApoyo $comunarios_apoyo)
    {
        return view('comunarios_apoyo.show', compact('comunarios_apoyo'));
    }
}
