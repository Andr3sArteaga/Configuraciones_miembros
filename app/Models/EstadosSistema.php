<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadosSistema
 * 
 * @property string $id
 * @property string $tabla
 * @property string $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $color
 * @property bool|null $es_final
 * @property int|null $orden
 * @property bool|null $activo
 * @property Carbon|null $creado
 * 
 * @property Collection|Usuario[] $usuarios
 * @property Collection|Equipo[] $equipos
 * @property Collection|Reporte[] $reportes
 * @property Collection|Recurso[] $recursos
 *
 * @package App\Models
 */
class EstadosSistema extends Model
{
	protected $table = 'estados_sistema';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'es_final' => 'bool',
		'orden' => 'int',
		'activo' => 'bool',
		'creado' => 'datetime'
	];

	protected $fillable = [
		'tabla',
		'codigo',
		'nombre',
		'descripcion',
		'color',
		'es_final',
		'orden',
		'activo',
		'creado'
	];
	public function equipos()
	{
		return $this->hasMany(Equipo::class, 'estado_id');
	}

	public function recursos()
	{
		return $this->hasMany(Recurso::class, 'estado_id');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'estado_id');
	}

	public function reportes()
	{
		return $this->hasMany(Reporte::class, 'estado_id');
	}
}
