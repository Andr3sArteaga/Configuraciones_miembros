<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MiembrosEquipo
 * 
 * @property string $id
 * @property uuid|null $id_equipo
 * @property uuid|null $id_usuario
 * @property Carbon|null $fecha_ingreso
 * 
 * @property Equipo|null $equipo
 * @property Usuario|null $usuario
 *
 * @package App\Models
 */
class MiembrosEquipo extends Model
{
	protected $table = 'miembros_equipo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'id_equipo' => 'uuid',
		'id_usuario' => 'uuid',
		'fecha_ingreso' => 'datetime'
	];

	protected $fillable = [
		'id_equipo',
		'id_usuario',
		'fecha_ingreso'
	];

	public function equipo()
	{
		return $this->belongsTo(Equipo::class, 'id_equipo');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario');
	}
}
