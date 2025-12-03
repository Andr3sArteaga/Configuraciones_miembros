<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 *
 * @property string $id
 * @property string $usuario_id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property array|null $data
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 *
 * @property Usuario $usuario
 */
class Notification extends Model
{
    protected $table = 'notifications';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    const UPDATED_AT = null;

    protected $casts = [
        'id' => 'string',
        'usuario_id' => 'string',
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'usuario_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'created_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
