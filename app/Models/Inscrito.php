<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Inscrito
 * 
 * @property string $id
 * @property string $nombres
 * @property string|null $apellidos
 * @property string $ci
 * @property string|null $telefono
 * @property string|null $correo
 * @property Carbon|null $fecha_registro
 *
 * @property Collection|CursoAsignado[] $cursos_asignados
 *
 * @package App\Models
 */
class Inscrito extends Model
{
    // Definir la tabla en la base de datos
    protected $table = 'inscrito';

    // Configuración para UUID como clave primaria
    public $incrementing = false;
    protected $keyType = 'string';

    // Deshabilitar timestamps si no los tienes en la tabla
    public $timestamps = false;

    // Cast de atributos a sus tipos correspondientes
    protected $casts = [
        'id' => 'string',           // UUID como string
        'fecha_registro' => 'datetime',  // Formato datetime para 'fecha_registro'
    ];

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'id',
        'nombres',
        'apellidos',
        'ci',
        'telefono',
        'correo',
        'fecha_registro',
    ];

    /**
     * Boot del modelo para generar UUID automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relación con los cursos asignados a este inscrito (clave foránea)
    public function cursos_asignados()
    {
        return $this->hasMany(CursoAsignado::class, 'entidad_id')
            ->where('entidad_tipo', 'inscrito');  // Este es el tipo polimórfico
    }

    // Obtener los cursos relacionados con el inscrito
    public function cursos()
    {
        return $this->cursos_asignados()->with('curso');  // Relaciona los cursos de cada asignación
    }
}
