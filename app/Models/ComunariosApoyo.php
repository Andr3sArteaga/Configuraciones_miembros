<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ComunariosApoyo
 * 
 * @property uuid $id
 * @property string $nombre
 * @property int|null $edad
 * @property string|null $entidad_perteneciente
 * @property uuid|null $equipoid
 * @property Carbon|null $creado
 * 
 * @property Equipo|null $equipo
 *
 * @package App\Models
 */
class ComunariosApoyo extends Model
{
	protected $table = 'comunarios_apoyo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'uuid',
		'edad' => 'int',
		'equipoid' => 'uuid',
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
}
