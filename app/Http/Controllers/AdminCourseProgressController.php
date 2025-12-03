<?php

namespace App\Http\Controllers;

use App\Services\CourseProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCourseProgressController extends Controller
{
    protected $progressService;

    public function __construct(CourseProgressService $progressService)
    {
        $this->progressService = $progressService;
        $this->middleware('auth');
    }

    /**
     * Display all pending stage completions
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $pendingCompletions = $this->progressService->getPendingCompletions();

        return view('admin.course-progress.index', compact('pendingCompletions'));
    }

    /**
     * Display progress for a specific course
     */
    public function show(string $cursoId)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $curso = \App\Models\Curso::with(['stages', 'cursos_asignados'])->findOrFail($cursoId);
        
        // Get all users enrolled in this course
        $usuarios = $curso->cursos_asignados()
            ->where('entidad_tipo', 'usuario')
            ->with('usuario')
            ->get()
            ->pluck('usuario')
            ->filter();

        // Get progress for each user
        $userProgress = [];
        foreach ($usuarios as $usuario) {
            $userProgress[$usuario->id] = [
                'usuario' => $usuario,
                'progress' => $this->progressService->getUserStageStatus($usuario->id, $cursoId),
            ];
        }

        return view('admin.course-progress.show', compact('curso', 'userProgress'));
    }

    /**
     * Approve a stage completion
     */
    public function approve(Request $request, string $progressId)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción.'
            ], 403);
        }

        $result = $this->progressService->approveStageCompletion(Auth::id(), $progressId);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
