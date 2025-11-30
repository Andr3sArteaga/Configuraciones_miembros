<?php

namespace App\Http\Controllers;

use App\Models\NoticiasIncendio;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the latest 10 news articles
        $noticias = NoticiasIncendio::orderBy('date', 'desc')
            ->take(10)
            ->get();
        
        // Create a fake paginator for compatibility with the view
        $noticias = new \Illuminate\Pagination\LengthAwarePaginator(
            $noticias,
            $noticias->count(),
            10,
            1,
            ['path' => request()->url()]
        );
        
        return view('noticias.index', compact('noticias'));
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
