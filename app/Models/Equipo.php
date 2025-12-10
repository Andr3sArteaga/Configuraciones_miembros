<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Reporte;

/**
 * Class Equipo
 * 
 * @property string $id
 * @property string $nombre_equipo
 * @property string|null $cantidad_integrantes
 * @property string|null $estado_id
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property EstadosSistema|null $estados_sistema
 * @property Collection|ComunariosApoyo[] $comunarios_apoyos
 * @property Collection|MiembrosEquipo[] $miembros_equipos
 * @property Collection|Recurso[] $recursos
 *
 * @package App\Models
 */
class Equipo extends Model
{
    protected $table = 'equipos';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'id' => 'string',
        'cantidad_integrantes' => 'string',
        'estado_id' => 'string',
        'reporte_id' => 'string',
        'creado' => 'datetime',
        'actualizado' => 'datetime',
    ];

    protected $fillable = [
        'nombre_equipo',
        'reporte_id',
        'cantidad_integrantes',
        'estado_id',
        'ubicacion',
        'creado',
        'actualizado'
    ];

    public function estados_sistema()
    {
        return $this->belongsTo(EstadosSistema::class, 'estado_id');
    }

    public function comunarios_apoyos()
    {
        return $this->hasMany(ComunariosApoyo::class, 'equipoid');
    }

    public function miembros_equipos()
    {
        return $this->hasMany(MiembrosEquipo::class, 'id_equipo');
    }

    public function miembros()
    {
        return $this->belongsToMany(Usuario::class, 'miembros_equipo', 'id_equipo', 'id_usuario')
            ->withPivot('es_lider', 'fecha_ingreso');
    }

    public function recursos()
    {
        return $this->hasMany(Recurso::class, 'equipoid');
    }

    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }

    /* La columna 'ubicacion' fue eliminada de la tabla 'equipos'; la ubicación se hereda desde 'reportes'. */

    /**
     * Get latitud from PostGIS geometry
     */
    /**
     * Accessor/Mutator para la columna PostGIS 'ubicacion'.
     */
    protected function ubicacion(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if ($value === null) return null;
                try {
                    $result = DB::selectOne("SELECT ST_AsGeoJSON(?) AS geojson", [$value]);
                    return $result ? json_decode($result->geojson, true) : null;
                } catch (\Exception $e) { return null; }
            },
            set: function ($value) {
                if ($value === null) return null;
                if ($value instanceof \Illuminate\Contracts\Database\Query\Expression) return $value;
                if (!is_array($value) || !isset($value['lat'], $value['lng'])) return null;
                $lat = (float) $value['lat'];
                $lng = (float) $value['lng'];
                return DB::raw("ST_SetSRID(ST_MakePoint({$lng}, {$lat}), 4326)");
            }
        );
    }

    /**
     * Get latitud from PostGIS geometry
     */
    public function getLatitudAttribute()
    {
        // Try local ubicacion first
        if ($this->ubicacion && isset($this->ubicacion['coordinates'])) {
             return $this->ubicacion['coordinates'][1];
        }

        // Fallback to reporte ubicacion
        try {
            if ($this->reporte && isset($this->reporte->ubicacion['coordinates'])) {
                return $this->reporte->ubicacion['coordinates'][1];
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get longitud from PostGIS geometry
     */
    public function getLongitudAttribute()
    {
        // Try local ubicacion first
        if ($this->ubicacion && isset($this->ubicacion['coordinates'])) {
             return $this->ubicacion['coordinates'][0];
        }

        try {
            if ($this->reporte && isset($this->reporte->ubicacion['coordinates'])) {
                return $this->reporte->ubicacion['coordinates'][0];
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
