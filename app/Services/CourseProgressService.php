<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\CourseProgress;
use App\Models\CourseStage;
use App\Models\CursoAsignado;
use App\Models\Notification;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseProgressService
{
    /**
     * Initialize progress records for a user when they enroll in a course
     * Only Stage 1 is available, all others are locked
     */
    public function initializeUserProgress(string $userId, string $cursoId): void
    {
        $curso = Curso::with('stages')->findOrFail($cursoId);
        $stages = $curso->stages()->orderBy('orden')->get();

        foreach ($stages as $index => $stage) {
            $isFirstStage = $index === 0;

            CourseProgress::create([
                'id' => Str::uuid()->toString(),
                'curso_id' => $cursoId,
                'course_stage_id' => $stage->id,
                'usuario_id' => $userId,
                'estado' => $isFirstStage ? 'disponible' : 'bloqueado',
                'creado' => now(),
                'actualizado' => now(),
            ]);
        }
    }

    /**
     * User marks a stage as completed
     * Sends notification to all admins
     */
    public function markStageCompleted(string $userId, string $stageId): array
    {
        try {
            DB::beginTransaction();

            $progress = CourseProgress::where('usuario_id', $userId)
                ->where('course_stage_id', $stageId)
                ->with(['stage', 'curso', 'usuario'])
                ->firstOrFail();

            // Validate stage is available
            if (!$progress->isAvailable()) {
                return [
                    'success' => false,
                    'message' => 'Esta etapa no está disponible aún.'
                ];
            }

            // Mark as completed
            $progress->estado = 'completado';
            $progress->completed_at = now();
            $progress->actualizado = now();
            $progress->save();

            // Send notification to all admins
            $admins = Usuario::whereHas('role', function ($query) {
                $query->where('codigo', 'ADMIN');
            })->get();

            $userName = $progress->usuario->nombre . ' ' . $progress->usuario->apellido;
            $courseName = $progress->curso->nombre;
            $stageNumber = $progress->stage->stage_number;

            foreach ($admins as $admin) {
                $this->sendNotification(
                    $admin->id,
                    'stage_completed',
                    'Etapa completada por usuario',
                    "El usuario {$userName} ha completado la Etapa {$stageNumber} del curso {$courseName}.",
                    [
                        'curso_id' => $progress->curso_id,
                        'stage_id' => $stageId,
                        'usuario_id' => $userId,
                        'progress_id' => $progress->id,
                    ]
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Etapa marcada como completada. Esperando aprobación del administrador.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al marcar la etapa como completada: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Admin approves stage completion and unlocks next stage
     * Sends notification to user
     */
    public function approveStageCompletion(string $adminId, string $progressId): array
    {
        try {
            DB::beginTransaction();

            $progress = CourseProgress::with(['stage', 'curso', 'usuario'])
                ->findOrFail($progressId);

            // Validate it's pending approval
            if (!$progress->isPending()) {
                return [
                    'success' => false,
                    'message' => 'Esta etapa no está pendiente de aprobación.'
                ];
            }

            // Mark as approved
            $progress->estado = 'aprobado';
            $progress->reviewed_by = $adminId;
            $progress->reviewed_at = now();
            $progress->actualizado = now();
            $progress->save();

            // Unlock next stage
            $nextStage = CourseStage::where('curso_id', $progress->curso_id)
                ->where('orden', '>', $progress->stage->orden)
                ->orderBy('orden')
                ->first();

            if ($nextStage) {
                $nextProgress = CourseProgress::where('usuario_id', $progress->usuario_id)
                    ->where('course_stage_id', $nextStage->id)
                    ->first();

                if ($nextProgress) {
                    $nextProgress->estado = 'disponible';
                    $nextProgress->actualizado = now();
                    $nextProgress->save();

                    // Notify user that next stage is unlocked
                    $this->sendNotification(
                        $progress->usuario_id,
                        'stage_unlocked',
                        'Nueva etapa desbloqueada',
                        "Tu progreso en la Etapa {$progress->stage->stage_number} ha sido aprobado. La Etapa {$nextStage->stage_number} ahora está disponible.",
                        [
                            'curso_id' => $progress->curso_id,
                            'stage_id' => $nextStage->id,
                            'previous_stage_id' => $progress->course_stage_id,
                        ]
                    );
                }
            } else {
                // This was the last stage - check if course is complete
                $isComplete = $this->checkCourseCompletion($progress->usuario_id, $progress->curso_id);
                
                if ($isComplete) {
                    $this->sendNotification(
                        $progress->usuario_id,
                        'course_completed',
                        '¡Felicidades!',
                        "Felicidades, has terminado el curso {$progress->curso->nombre} de manera eficaz.",
                        [
                            'curso_id' => $progress->curso_id,
                        ]
                    );
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Etapa aprobada exitosamente.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al aprobar la etapa: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get user's stage status for a course
     */
    public function getUserStageStatus(string $userId, string $cursoId): array
    {
        $progress = CourseProgress::where('usuario_id', $userId)
            ->where('curso_id', $cursoId)
            ->with('stage.resources')
            ->orderBy('creado')
            ->get();

        return $progress->map(function ($p) {
            return [
                'progress_id' => $p->id,
                'stage_id' => $p->course_stage_id,
                'stage_number' => $p->stage->stage_number,
                'stage_title' => $p->stage->titulo_autogenerado,
                'module_name' => $p->stage->module_name,
                'estado' => $p->estado,
                'is_locked' => $p->isLocked(),
                'is_available' => $p->isAvailable(),
                'is_completed' => $p->isCompleted(),
                'is_approved' => $p->isApproved(),
                'is_pending' => $p->isPending(),
                'completed_at' => $p->completed_at?->format('Y-m-d H:i:s'),
                'reviewed_at' => $p->reviewed_at?->format('Y-m-d H:i:s'),
                'resources' => $p->stage->resources,
            ];
        })->toArray();
    }

    /**
     * Check if user has completed all stages of a course
     */
    public function checkCourseCompletion(string $userId, string $cursoId): bool
    {
        $totalStages = CourseStage::where('curso_id', $cursoId)->count();
        $approvedStages = CourseProgress::where('usuario_id', $userId)
            ->where('curso_id', $cursoId)
            ->where('estado', 'aprobado')
            ->count();

        return $totalStages > 0 && $totalStages === $approvedStages;
    }

    /**
     * Send notification to a user
     */
    public function sendNotification(string $userId, string $type, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'id' => Str::uuid()->toString(),
            'usuario_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'created_at' => now(),
        ]);
    }

    /**
     * Get pending stage completions for admin review
     */
    public function getPendingCompletions(): array
    {
        $pending = CourseProgress::pending()
            ->with(['usuario', 'curso', 'stage'])
            ->orderBy('completed_at', 'desc')
            ->get();

        return $pending->map(function ($p) {
            return [
                'progress_id' => $p->id,
                'usuario_nombre' => $p->usuario->nombre . ' ' . $p->usuario->apellido,
                'usuario_email' => $p->usuario->email,
                'curso_nombre' => $p->curso->nombre,
                'stage_number' => $p->stage->stage_number,
                'stage_title' => $p->stage->titulo_autogenerado,
                'completed_at' => $p->completed_at?->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}
