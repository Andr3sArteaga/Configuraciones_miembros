<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property string $id
 * @property string $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $permisos
 * @property bool|null $activo
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'legacy_roles';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'permisos' => 'array',
		'activo' => 'bool',
		'creado' => 'datetime',
		'actualizado' => 'datetime'
	];

	protected $fillable = [
		'codigo',
		'nombre',
		'descripcion',
		'permisos',
		'activo',
		'creado',
		'actualizado'
	];

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'rol_id');
	}
}
