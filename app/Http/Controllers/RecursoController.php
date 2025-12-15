<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\TiposRecurso;
use App\Models\EstadosSistema;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\Services\InventoryService $inventoryService)
    {
        // Obtener recursos desde la API de inventario
        $result = $inventoryService->getProductsInventory();
        
        $recursos = $result['data'];
        $apiAvailable = $result['success'];
        $errorMessage = $result['message'];
        
        return view('recursos.index', compact('recursos', 'apiAvailable', 'errorMessage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
