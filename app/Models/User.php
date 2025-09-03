<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable
{
    use Notifiable;

    // Indica que la PK no es autoincremental y es string
    protected $keyType = 'string';
    public $incrementing = false;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'is_admin', // si usas este campo
    ];

    // Oculta el password y remember_token al serializar
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Genera automáticamente un UUID si no existe al crear un usuario
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->{$user->getKeyName()})) {
                $user->{$user->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Indica si el usuario puede acceder al panel de Filament
    public function canAccessFilament(): bool
    {
        return $this->is_admin ?? false;
    }

    // Filament busca el usuario por email
    public static function findForFilament(string $email): ?self
    {
        return static::whereEmail($email)->first();
    }
}
