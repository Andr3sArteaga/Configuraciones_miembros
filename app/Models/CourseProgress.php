<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseProgress
 *
 * Progreso de un usuario en una etapa de un curso.
 *
 * @property string $id
 * @property string $curso_id
 * @property string $course_stage_id
 * @property string $usuario_id
 * @property string|null $assignment_id
 * @property string $estado
 * @property float|null $score
 * @property string|null $feedback
 * @property string|null $evidence_path
 * @property Carbon|null $completed_at
 * @property string|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 *
 * @property Curso $curso
 * @property CourseStage $stage
 * @property Usuario $usuario
 * @property CursoAsignado|null $assignment
 */
class CourseProgress extends Model
{
    protected $table = 'course_progress';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'score' => 'float',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'creado' => 'datetime',
        'actualizado' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'curso_id',
        'course_stage_id',
        'usuario_id',
        'assignment_id',
        'estado',
        'score',
        'feedback',
        'evidence_path',
        'completed_at',
        'reviewed_by',
        'reviewed_at',
        'creado',
        'actualizado',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function stage()
    {
        return $this->belongsTo(CourseStage::class, 'course_stage_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function assignment()
    {
        return $this->belongsTo(CursoAsignado::class, 'assignment_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Usuario::class, 'reviewed_by');
    }

    /**
     * Check if stage is locked (not yet available)
     */
    public function isLocked(): bool
    {
        return $this->estado === 'bloqueado';
    }

    /**
     * Check if stage is available to user
     */
    public function isAvailable(): bool
    {
        return $this->estado === 'disponible';
    }

    /**
     * Check if user has marked stage as completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->estado, ['completado', 'aprobado']);
    }

    /**
     * Check if admin has approved the stage
     */
    public function isApproved(): bool
    {
        return $this->estado === 'aprobado';
    }

    /**
     * Check if stage is pending admin approval
     */
    public function isPending(): bool
    {
        return $this->estado === 'completado' && !$this->isApproved();
    }

    /**
     * Scope for available stages
     */
    public function scopeAvailable($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Scope for pending approval
     */
    public function scopePending($query)
    {
        return $query->where('estado', 'completado')
            ->whereNull('reviewed_at');
    }

    /**
     * Scope for approved stages
     */
    public function scopeApproved($query)
    {
        return $query->where('estado', 'aprobado');
    }
}


