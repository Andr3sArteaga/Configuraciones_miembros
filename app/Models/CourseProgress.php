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
}


