<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property string $id
 * @property string $nombre
 * @property string $apellido
 * @property string $ci
 * @property Carbon $fecha_nacimiento
 * @property string|null $genero
 * @property string|null $telefono
 * @property string $email
 * @property string $password
 * @property string|null $tipo_de_sangre
 * @property string|null $nivel_de_entrenamiento
 * @property string|null $entidad_perteneciente
 * @property string|null $rol
 * @property string|null $estado
 * @property bool|null $debe_cambiar_password
 * @property string|null $reset_token
 * @property Carbon|null $reset_token_expires
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property Collection|Equipo[] $equipos
 * @property Collection|MiembrosEquipo[] $miembros_equipos
 * @property Collection|ReportesIncendio[] $reportes_incendios
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuarios';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'fecha_nacimiento' => 'datetime',
		'debe_cambiar_password' => 'bool',
		'reset_token_expires' => 'datetime',
		'creado' => 'datetime',
		'actualizado' => 'datetime'
	];

	protected $hidden = [
		'password',
		'debe_cambiar_password',
		'reset_token'
	];

	protected $fillable = [
		'nombre',
		'apellido',
		'ci',
		'fecha_nacimiento',
		'genero',
		'telefono',
		'email',
		'password',
		'tipo_de_sangre',
		'nivel_de_entrenamiento',
		'entidad_perteneciente',
		'rol',
		'estado',
		'debe_cambiar_password',
		'reset_token',
		'reset_token_expires',
		'creado',
		'actualizado'
	];

	public function equipos()
	{
		return $this->hasMany(Equipo::class, 'id_lider_equipo');
	}

	public function miembros_equipos()
	{
		return $this->hasMany(MiembrosEquipo::class, 'id_usuario');
	}

	public function reportes_incendios()
	{
		return $this->hasMany(ReportesIncendio::class, 'id_usuario_creador');
	}
}
