<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ComunariosApoyo
 * 
 * @property string $id
 * @property string $nombre
 * @property int|null $edad
 * @property string|null $entidad_perteneciente
 * @property string|null $equipoid
 * @property Carbon|null $creado
 *
 * @property Equipo|null $equipo
 * @property Collection|CursoAsignado[] $cursos_asignados
 *
 * @package App\Models
 */
class ComunariosApoyo extends Model
{
	protected $table = 'comunarios_apoyo';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'edad' => 'int',
		'entidad_perteneciente' => 'string',
		'equipoid' => 'string',
		'creado' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'edad',
		'entidad_perteneciente',
		'equipoid',
		'creado'
	];

	public function equipo()
	{
		return $this->belongsTo(Equipo::class, 'equipoid');
	}

	public function cursos_asignados()
	{
		return $this->hasMany(CursoAsignado::class, 'entidad_id')
			->where('entidad_tipo', 'comunario');
	}

	public function cursos()
	{
		return $this->cursos_asignados()->with('curso');
	}
}
