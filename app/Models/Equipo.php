<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Equipo
 * 
 * @property uuid $id
 * @property string $nombre_equipo
 * @property USER-DEFINED|null $ubicacion
 * @property int|null $cantidad_integrantes
 * @property uuid|null $id_lider_equipo
 * @property string|null $estado
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property Usuario|null $usuario
 * @property Collection|MiembrosEquipo[] $miembros_equipos
 * @property Collection|Recurso[] $recursos
 * @property Collection|ComunariosApoyo[] $comunarios_apoyos
 *
 * @package App\Models
 */
class Equipo extends Model
{
	protected $table = 'equipos';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'uuid',
		'ubicacion' => 'USER-DEFINED',
		'cantidad_integrantes' => 'int',
		'id_lider_equipo' => 'uuid',
		'creado' => 'datetime',
		'actualizado' => 'datetime'
	];

	protected $fillable = [
		'nombre_equipo',
		'ubicacion',
		'cantidad_integrantes',
		'id_lider_equipo',
		'estado',
		'creado',
		'actualizado'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_lider_equipo');
	}

	public function miembros_equipos()
	{
		return $this->hasMany(MiembrosEquipo::class, 'id_equipo');
	}

	public function recursos()
	{
		return $this->hasMany(Recurso::class, 'equipoid');
	}

	public function comunarios_apoyos()
	{
		return $this->hasMany(ComunariosApoyo::class, 'equipoid');
	}
}
