<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reporte
 * 
 * @property string $id
 * @property string $nombre_reportante
 * @property string|null $telefono_contacto
 * @property Carbon $fecha_hora
 * @property string|null $nombre_lugar
 * @property array|null $ubicacion
 * @property string|null $tipo_incendio
 * @property string|null $gravedad_incendio
 * @property string|null $comentario_adicional
 * @property int|null $cant_bomberos
 * @property int|null $cant_paramedicos
 * @property int|null $cant_veterinarios
 * @property int|null $cant_autoridades
 * @property string|null $estado
 * @property Carbon|null $creado
 *
 * @package App\Models
 */
class Reporte extends Model
{
	protected $table = 'reportes';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'fecha_hora' => 'datetime',
		'ubicacion' => \App\Casts\PostgisPointCast::class,
		'cant_bomberos' => 'int',
		'cant_paramedicos' => 'int',
		'cant_veterinarios' => 'int',
		'cant_autoridades' => 'int',
		'creado' => 'datetime'
	];

	protected $fillable = [
		'nombre_reportante',
		'telefono_contacto',
		'fecha_hora',
		'nombre_lugar',
		'ubicacion',
		'tipo_incendio',
		'gravedad_incendio',
		'comentario_adicional',
		'cant_bomberos',
		'cant_paramedicos',
		'cant_veterinarios',
		'cant_autoridades',
		'estado',
		'creado'
	];
}
