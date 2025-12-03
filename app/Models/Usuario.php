<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Usuario
 * 
 * @property string $id
 * @property string $nombre
 * @property string $apellido
 * @property string $ci
 * @property Carbon $fecha_nacimiento
 * @property string|null $genero_id
 * @property string|null $telefono
 * @property string $email
 * @property string $password
 * @property string|null $tipo_sangre_id
 * @property string|null $nivel_entrenamiento_id
 * @property string|null $entidad_perteneciente
 * @property string|null $rol_id
 * @property string|null $estado_id
 * @property bool|null $debe_cambiar_password
 * @property string|null $reset_token
 * @property Carbon|null $reset_token_expires
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property Genero|null $genero
 * @property TiposSangre|null $tipos_sangre
 * @property NivelesEntrenamiento|null $niveles_entrenamiento
 * @property Role|null $role
 * @property EstadosSistema|null $estados_sistema
 * @property Collection|MiembrosEquipo[] $miembros_equipos
 * @property Collection|ReportesIncendio[] $reportes_incendios
 *
 * @package App\Models
 */
class Usuario extends Authenticatable
{
	use HasApiTokens;

	protected $table = 'usuarios';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'apellido' => 'string',
		'fecha_nacimiento' => 'datetime',
		'genero_id' => 'string',
		'tipo_sangre_id' => 'string',
		'nivel_entrenamiento_id' => 'string',
		'entidad_perteneciente' => 'string',
		'rol_id' => 'string',
		'estado_id' => 'string',
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
		'genero_id',
		'telefono',
		'email',
		'password',
		'tipo_sangre_id',
		'nivel_entrenamiento_id',
		'entidad_perteneciente',
		'rol_id',
		'estado_id',
		'debe_cambiar_password',
		'reset_token',
		'reset_token_expires',
		'creado',
		'actualizado'
	];

	public function genero()
	{
		return $this->belongsTo(Genero::class);
	}

	public function tipos_sangre()
	{
		return $this->belongsTo(TiposSangre::class, 'tipo_sangre_id');
	}

	public function niveles_entrenamiento()
	{
		return $this->belongsTo(NivelesEntrenamiento::class, 'nivel_entrenamiento_id');
	}

	public function role()
	{
		return $this->belongsTo(Role::class, 'rol_id');
	}

	public function estados_sistema()
	{
		return $this->belongsTo(EstadosSistema::class, 'estado_id');
	}

	public function miembros_equipos()
	{
		return $this->hasMany(MiembrosEquipo::class, 'id_usuario');
	}

	public function reportes_incendios()
	{
		return $this->hasMany(ReportesIncendio::class, 'id_usuario_creador');
	}

	/**
	 * User's notifications
	 */
	public function notifications()
	{
		return $this->hasMany(Notification::class, 'usuario_id')
			->orderBy('created_at', 'desc');
	}

	/**
	 * User's course progress records
	 */
	public function courseProgress()
	{
		return $this->hasMany(CourseProgress::class, 'usuario_id');
	}

	/**
	 * Check if user is an administrator
	 */
	public function isAdmin(): bool
	{
		return $this->role && strtolower($this->role->codigo) === 'admin';
	}

	/**
	 * Check if user is a volunteer/firefighter
	 */
	public function isVoluntario(): bool
	{
		$codigo = $this->role?->codigo;
		return $codigo && in_array(strtolower($codigo), ['bombero', 'paramedico', 'veterinario', 'voluntario']);
	}

	/**
	 * Check if user has one of the specified roles
	 */
	public function hasRole(string|array $roles): bool
	{
		if (!$this->role) {
			return false;
		}

		$roles = is_array($roles) ? $roles : [$roles];
		$userRole = strtolower($this->role->codigo);

		foreach ($roles as $role) {
			if (strtolower($role) === $userRole) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get user's team (if any)
	 */
	public function equipo()
	{
		$miembro = $this->miembros_equipos()->first();
		return $miembro ? $miembro->equipo : null;
	}
}
