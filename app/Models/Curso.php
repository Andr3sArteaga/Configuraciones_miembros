<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Curso
 *
 * @property string $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $slug
 * @property string|null $objetivos
 * @property Carbon|null $inicio_programado
 * @property Carbon|null $fin_programado
 * @property int|null $max_participantes
 * @property string|null $estado
 * @property string|null $visibilidad
 * @property string|null $nivel_requerido_id
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $approved_by
 * @property Carbon|null $fecha_aprobacion
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 *
 * @property Collection|CursoAsignado[] $cursos_asignados
 * @property Collection|\App\Models\CourseStage[] $course_stages
 * @property Collection|\App\Models\CourseProgress[] $course_progress
 *
 * @package App\Models
 */
class Curso extends Model
{
	protected $table = 'cursos';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'inicio_programado' => 'date',
		'fin_programado' => 'date',
		'max_participantes' => 'int',
		'fecha_aprobacion' => 'datetime',
		'creado' => 'datetime',
		'actualizado' => 'datetime',
	];

	protected $fillable = [
        'id',
		'nombre',
		'descripcion',
		'slug',
		'objetivos',
		'inicio_programado',
		'fin_programado',
		'max_participantes',
		'estado',
		'visibilidad',
		'nivel_requerido_id',
		'created_by',
		'updated_by',
		'approved_by',
		'fecha_aprobacion',
		'creado',
		'actualizado',
	];

	/**
	 * Relación con CursosAsignados
	 */
	public function cursos_asignados()
	{
		return $this->hasMany(CursoAsignado::class, 'curso_id');
	}

    /**
     * Etapas del curso (course_stages).
     */
    public function stages()
    {
        return $this->hasMany(\App\Models\CourseStage::class, 'curso_id')
            ->orderBy('orden');
    }

    /**
     * Progreso de usuarios en el curso.
     */
    public function progress()
    {
        return $this->hasMany(\App\Models\CourseProgress::class, 'curso_id');
    }

	/**
	 * Obtener usuarios asignados a este curso
	 */
	public function usuarios()
	{
		return $this->cursos_asignados()
			->where('entidad_tipo', 'usuario')
			->with('usuario');
	}

	/**
	 * Obtener comunarios asignados a este curso
	 */
	public function comunarios()
	{
		return $this->cursos_asignados()
			->where('entidad_tipo', 'comunario')
			->with('comunario');
	}

	/**
	 * Contar total de personas asignadas
	 */
	public function getTotalAsignadosAttribute()
	{
		return $this->cursos_asignados()->count();
	}

	/**
	 * Contar usuarios asignados
	 */
	public function getTotalUsuariosAttribute()
	{
		return $this->cursos_asignados()->where('entidad_tipo', 'usuario')->count();
	}

	/**
	 * Contar comunarios asignados
	 */
	public function getTotalComunariosAttribute()
	{
		return $this->cursos_asignados()->where('entidad_tipo', 'comunario')->count();
	}

	/**
	 * Get user's progress for this course
	 */
	public function getUserProgress($userId)
	{
		return $this->progress()
			->where('usuario_id', $userId)
			->with('stage')
			->orderBy('creado')
			->get();
	}
}
