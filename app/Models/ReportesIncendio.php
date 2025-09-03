<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportesIncendio
 * 
 * @property string $id
 * @property string $nombre_incidente
 * @property bool|null $controlado
 * @property float|null $extension
 * @property string|null $condiciones_clima
 * @property string|null $equipos_en_uso
 * @property int|null $numero_bomberos
 * @property bool|null $necesita_mas_bomberos
 * @property string|null $apoyo_externo
 * @property string|null $comentario_adicional
 * @property Carbon|null $fecha_creacion
 * @property uuid|null $id_usuario_creador
 * 
 * @property Usuario|null $usuario
 *
 * @package App\Models
 */
class ReportesIncendio extends Model
{
	protected $table = 'reportes_incendio';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'controlado' => 'bool',
		'extension' => 'float',
		'numero_bomberos' => 'int',
		'necesita_mas_bomberos' => 'bool',
		'fecha_creacion' => 'datetime',
		'id_usuario_creador' => 'string',  // Asegúrate de que el campo `id_usuario_creador` sea tratado como cadena.
	];

	protected $fillable = [
		'nombre_incidente',
		'controlado',
		'extension',
		'condiciones_clima',
		'equipos_en_uso',
		'numero_bomberos',
		'necesita_mas_bomberos',
		'apoyo_externo',
		'comentario_adicional',
		'fecha_creacion',
		'id_usuario_creador'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario_creador');
	}
}
