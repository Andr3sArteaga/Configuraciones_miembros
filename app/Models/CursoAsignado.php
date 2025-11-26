<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CursoAsignado
 *
 * Implementa una relación polimórfica manual para asignar cursos
 * a múltiples tipos de entidades (usuarios y comunarios).
 *
 * @property string $id
 * @property string|null $curso_id
 * @property string $entidad_id - UUID de la entidad (usuario o comunario)
 * @property string $entidad_tipo - Tipo: 'usuario' o 'comunario'
 * @property Carbon|null $fecha_asignacion
 *
 * @property Curso|null $curso
 * @property Usuario|null $usuario - Cuando entidad_tipo = 'usuario'
 * @property ComunariosApoyo|null $comunario - Cuando entidad_tipo = 'comunario'
 *
 * @package App\Models
 */
class CursoAsignado extends Model
{
    protected $table = 'cursos_asignados';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'string',
        'curso_id' => 'string',
        'entidad_id' => 'string',
        'entidad_tipo' => 'string',
        'fecha_asignacion' => 'datetime'
    ];

    protected $fillable = [
        'id',
        'curso_id',
        'entidad_id',
        'entidad_tipo',
        'fecha_asignacion'
    ];

    /**
     * Relación con Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Obtener el usuario relacionado (si entidad_tipo = 'usuario')
     * Relación simple - se filtra manualmente si es necesario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'entidad_id');
    }

    /**
     * Obtener el comunario relacionado (si entidad_tipo = 'comunario')
     * Relación simple - se filtra manualmente si es necesario
     */
    public function comunario()
    {
        return $this->belongsTo(ComunariosApoyo::class, 'entidad_id');
    }

    /**
     * Obtener la entidad relacionada (polimórfico manual)
     * Devuelve el modelo Usuario o ComunariosApoyo según el tipo
     */
    public function entidad()
    {
        if ($this->entidad_tipo === 'usuario') {
            return $this->usuario;
        } elseif ($this->entidad_tipo === 'comunario') {
            return $this->comunario;
        }
        return null;
    }

    /**
     * Scope para filtrar por tipo de entidad
     */
    public function scopeUsuarios($query)
    {
        return $query->where('entidad_tipo', 'usuario');
    }

    /**
     * Scope para filtrar por comunarios
     */
    public function scopeComunarios($query)
    {
        return $query->where('entidad_tipo', 'comunario');
    }

    /**
     * Scope para filtrar por curso específico
     */
    public function scopePorCurso($query, $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    /**
     * Scope para filtrar por entidad específica
     */
    public function scopePorEntidad($query, $entidadId, $entidadTipo = null)
    {
        $query->where('entidad_id', $entidadId);

        if ($entidadTipo) {
            $query->where('entidad_tipo', $entidadTipo);
        }

        return $query;
    }

    /**
     * Obtener el nombre de la entidad asignada
     */
    public function getNombreEntidadAttribute()
    {
        $entidad = $this->entidad();

        if (!$entidad) {
            return 'Entidad no encontrada';
        }

        if ($this->entidad_tipo === 'usuario') {
            return $entidad->nombre . ' ' . $entidad->apellido;
        } elseif ($this->entidad_tipo === 'comunario') {
            return $entidad->nombre;
        }

        return 'Desconocido';
    }
}
