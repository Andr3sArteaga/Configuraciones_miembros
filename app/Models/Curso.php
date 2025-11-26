<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'id' => 'string',
        'fecha_inicio' => 'datetime',
        'creado' => 'datetime'
    ];

    protected $fillable = [
        'titulo',
        'descripcion',
        'url',
        'imagen',
        'fecha_inicio',
        'creado'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
            }
        });
    }
}
