<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    // NO incluimos 'id' aquí porque no la pasaremos manualmente
    protected $fillable = [
        'user_id',
        'message',
        'read',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
