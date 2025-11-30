<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FocosCalor
 * 
 * @property string $id
 * @property float $latitude
 * @property float $longitude
 * @property string|null $confidence
 * @property Carbon $acq_date
 * @property time without time zone $acq_time
 * @property float|null $bright_ti4
 * @property float|null $bright_ti5
 * @property float|null $frp
 * @property Carbon|null $creado
 *
 * @package App\Models
 */
class FocosCalor extends Model
{
	protected $table = 'focos_calor';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'latitude' => 'float',
		'longitude' => 'float',
		'confidence' => 'string',
		'acq_date' => 'datetime',
		'acq_time' => 'string',
		'bright_ti4' => 'float',
		'bright_ti5' => 'float',
		'frp' => 'float',
		'creado' => 'datetime'
	];

	protected $fillable = [
		'latitude',
		'longitude',
		'confidence',
		'acq_date',
		'acq_time',
		'bright_ti4',
		'bright_ti5',
		'frp',
		'creado'
	];
}
