<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Recurso
 * 
 * @property uuid $id
 * @property string|null $codigo
 * @property string $descripcion
 * @property Carbon|null $fecha_pedido
 * @property string|null $estado_del_pedido
 * @property float|null $lat
 * @property float|null $lng
 * @property uuid|null $equipoid
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property Equipo|null $equipo
 *
 * @package App\Models
 */
class Recurso extends Model
{
	protected $table = 'recursos';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'uuid',
		'fecha_pedido' => 'datetime',
		'lat' => 'float',
		'lng' => 'float',
		'equipoid' => 'uuid',
		'creado' => 'datetime',
		'actualizado' => 'datetime'
	];

	protected $fillable = [
		'codigo',
		'descripcion',
		'fecha_pedido',
		'estado_del_pedido',
		'lat',
		'lng',
		'equipoid',
		'creado',
		'actualizado'
	];

	public function equipo()
	{
		return $this->belongsTo(Equipo::class, 'equipoid');
	}
}
