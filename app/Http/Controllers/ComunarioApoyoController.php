<?php

namespace App\Http\Controllers;

use App\Models\ComunariosApoyo;
use Illuminate\Http\Request;

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

    public function edit(ComunariosApoyo $comunario)
    {
        return view('comunarios_apoyo.edit', compact('comunario'));
    }

    public function update(Request $request, ComunariosApoyo $comunario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'entidad_perteneciente' => 'required|string|max:255',
        ]);

        $comunario->update($validated);
        return redirect()->route('comunarios_apoyo.index');
    }

    public function destroy(ComunariosApoyo $comunario)
    {
        $comunario->delete();
        return redirect()->route('comunarios_apoyo.index');
    }

    public function show(ComunariosApoyo $comunario)
    {
        return view('comunarios_apoyo.show', compact('comunario'));
    }
}
