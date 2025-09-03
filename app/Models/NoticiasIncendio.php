<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NoticiasIncendio
 * 
 * @property string $id
 * @property string $title
 * @property Carbon $date
 * @property string|null $description
 * @property string|null $url
 * @property string|null $image
 * @property Carbon|null $creado
 *
 * @package App\Models
 */
class NoticiasIncendio extends Model
{
	protected $table = 'noticias_incendios';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'date' => 'datetime',
		'creado' => 'datetime'
	];

	protected $fillable = [
		'title',
		'date',
		'description',
		'url',
		'image',
		'creado'
	];
}
