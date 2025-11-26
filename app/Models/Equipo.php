<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Equipo
 * 
 * @property string $id
 * @property string $nombre_equipo
 * @property array|null $ubicacion
 * @property string|null $cantidad_integrantes
 * @property string|null $estado_id
 * @property Carbon|null $creado
 * @property Carbon|null $actualizado
 * 
 * @property EstadosSistema|null $estados_sistema
 * @property Collection|Recurso[] $recursos
 * @property Collection|ComunariosApoyo[] $comunarios_apoyos
 * @property Collection|MiembrosEquipo[] $miembros_equipos
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
        'ubicacion' => 'array',
        'cantidad_integrantes' => 'string',
        'estado_id' => 'string',
        'creado' => 'datetime',
        'actualizado' => 'datetime',
    ];

    protected $fillable = [
        'nombre_equipo',
        'ubicacion',
        'cantidad_integrantes',
        'estado_id',
        'creado',
        'actualizado'
    ];

    public function estados_sistema()
    {
        return $this->belongsTo(EstadosSistema::class, 'estado_id');
    }

    public function recursos()
    {
        return $this->hasMany(Recurso::class, 'equipoid');
    }

    public function comunarios_apoyos()
    {
        return $this->hasMany(ComunariosApoyo::class, 'equipoid');
    }

    public function miembros_equipos()
    {
        return $this->hasMany(MiembrosEquipo::class, 'id_equipo');
    }

    /**
     * Relación many-to-many con usuarios a través de miembros_equipo
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'miembros_equipo', 'id_equipo', 'id_usuario')
            ->withPivot('es_lider', 'fecha_ingreso');
    }

    /**
     * Obtener el líder del equipo
     */
    public function lider()
    {
        return $this->belongsToMany(Usuario::class, 'miembros_equipo', 'id_equipo', 'id_usuario')
            ->wherePivot('es_lider', true)
            ->first();
    }

    /**
     * Accessor/Mutator para la columna PostGIS 'ubicacion'.
     *
     * - Get: Convierte geometría PostGIS a array GeoJSON.
     * - Set: Acepta ['lat' => ..., 'lng' => ...] o null.
     */
    protected function ubicacion(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if ($value === null) {
                    return null;
                }

                try {
                    $result = DB::selectOne("SELECT ST_AsGeoJSON(?) AS geojson", [$value]);
                    return $result ? json_decode($result->geojson, true) : null;
                } catch (\Exception $e) {
                    return null;
                }
            },
            set: function ($value) {
                if ($value === null) {
                    return null;
                }

                if (!is_array($value) || !isset($value['lat'], $value['lng'])) {
                    return null;
                }

                $lat = (float) $value['lat'];
                $lng = (float) $value['lng'];

                return DB::raw("ST_SetSRID(ST_MakePoint({$lng}, {$lat}), 4326)");
            }
        );
    }

    /**
     * Obtener latitud de la ubicación
     */
    protected function latitud(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value, $attributes) {
                $ubicacion = $this->ubicacion;
                return $ubicacion['coordinates'][1] ?? null;
            }
        );
    }

    /**
     * Obtener longitud de la ubicación
     */
    protected function longitud(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value, $attributes) {
                $ubicacion = $this->ubicacion;
                return $ubicacion['coordinates'][0] ?? null;
            }
        );
    }
}
