<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CourseStage
 *
 * Representa una etapa de un curso (Stage X).
 *
 * @property string $id
 * @property string $curso_id
 * @property int $stage_number
 * @property string $titulo_autogenerado
 * @property string $module_name
 * @property string|null $descripcion
 * @property int|null $duracion_minutos
 * @property string|null $delivery_mode
 * @property bool $is_final_stage
 * @property int $orden
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 *
 * @property Curso $curso
 * @property Collection|CourseResource[] $resources
 */
class CourseStage extends Model
{
    protected $table = 'course_stages';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'stage_number' => 'int',
        'duracion_minutos' => 'int',
        'is_final_stage' => 'bool',
        'orden' => 'int',
        'creado' => 'datetime',
        'actualizado' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'curso_id',
        'stage_number',
        'titulo_autogenerado',
        'module_name',
        'descripcion',
        'duracion_minutos',
        'delivery_mode',
        'is_final_stage',
        'orden',
        'creado',
        'actualizado',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function resources()
    {
        return $this->hasMany(CourseResource::class, 'stage_id');
    }
}


