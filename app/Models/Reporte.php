<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Reporte
 * 
 * @property string $id
 * @property string $nombre_reportante
 * @property string|null $telefono_contacto
 * @property Carbon $fecha_hora
 * @property string|null $nombre_lugar
 * @property array|null $ubicacion
 * @property string|null $tipo_incidente_id
 * @property string|null $gravedad_id
 * @property string|null $comentario_adicional
 * @property int|null $cant_bomberos
 * @property int|null $cant_paramedicos
 * @property int|null $cant_veterinarios
 * @property string|null $cant_autoridades
 * @property string|null $estado_id
 * @property Carbon|null $creado
 * 
 * @property TiposIncidente|null $tipos_incidente
 * @property NivelesGravedad|null $niveles_gravedad
 * @property EstadosSistema|null $estados_sistema
 *
 * @package App\Models
 */
class Reporte extends Model
{
	protected $table = 'reportes';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
        'id' => 'string',
        'fecha_hora' => 'datetime',
        'tipo_incidente_id' => 'string',
        'gravedad_id' => 'string',
        'cant_bomberos' => 'int',
        'cant_paramedicos' => 'int',
        'cant_veterinarios' => 'int',
        'cant_autoridades' => 'string',
        'estado_id' => 'string',
        'creado' => 'datetime',
    ];

	protected $fillable = [
		'nombre_reportante',
		'telefono_contacto',
		'fecha_hora',
		'nombre_lugar',
		'ubicacion',
		'tipo_incidente_id',
		'gravedad_id',
		'comentario_adicional',
		'cant_bomberos',
		'cant_paramedicos',
		'cant_veterinarios',
		'cant_autoridades',
		'estado_id',
		'creado'
	];

	public function tipos_incidente()
	{
		return $this->belongsTo(TiposIncidente::class, 'tipo_incidente_id');
	}

	public function niveles_gravedad()
	{
		return $this->belongsTo(NivelesGravedad::class, 'gravedad_id');
	}

	public function estados_sistema()
	{
		return $this->belongsTo(EstadosSistema::class, 'estado_id');
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

                if ($value instanceof \Illuminate\Contracts\Database\Query\Expression) {
                    return $value;
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
}
