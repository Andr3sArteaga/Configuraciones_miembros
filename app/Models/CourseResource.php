<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseResource
 *
 * Recurso asociado a una etapa del curso (video, documento, lectura, material extra).
 *
 * @property string $id
 * @property string $stage_id
 * @property string $resource_type
 * @property string|null $titulo
 * @property string|null $resource_url
 * @property string|null $file_path
 * @property string|null $descripcion
 * @property bool $requires_ack
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 *
 * @property CourseStage $stage
 */
class CourseResource extends Model
{
    protected $table = 'course_resources';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'requires_ack' => 'bool',
        'creado' => 'datetime',
        'actualizado' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'stage_id',
        'resource_type',
        'titulo',
        'resource_url',
        'file_path',
        'descripcion',
        'requires_ack',
        'creado',
        'actualizado',
    ];

    public function stage()
    {
        return $this->belongsTo(CourseStage::class, 'stage_id');
    }
}


