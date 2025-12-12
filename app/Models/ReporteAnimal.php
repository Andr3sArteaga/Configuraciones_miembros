<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteAnimal extends Model
{
    protected $table = 'reportes_animales';

    protected $fillable = [
        'incendio_id',
        'latitud',
        'longitud',
        'direccion',
        'observaciones',
        'condicion_inicial_id',
        'tipo_incidente_id',
        'tamano',
        'puede_moverse',
        'traslado_inmediato',
        'centro_id',
        'imagen_path',
    ];
}
