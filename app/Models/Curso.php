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
 * @property Carbon|null $creado
 *
 * @property Collection|CursoAsignado[] $cursos_asignados
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
		'creado' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'creado'
	];

	/**
	 * Relación con CursosAsignados
	 */
	public function cursos_asignados()
	{
		return $this->hasMany(CursoAsignado::class, 'curso_id');
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
}
