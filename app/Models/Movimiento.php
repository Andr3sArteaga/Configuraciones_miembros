<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'movimientos';

    protected $fillable = [
        'usuario_id',
        'ci_usuario',
        'accion',
        'modulo',
        'entidad_tipo',
        'entidad_id',
        'descripcion',
        'datos_anteriores',
        'datos_nuevos',
        'ip_address',
        'user_agent',
        'metodo_http',
        'ruta',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
    ];

    /**
     * Relación con el usuario que realizó la acción
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Scope para filtrar por usuario ID
     */
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    /**
     * Scope para filtrar por CI del usuario
     */
    public function scopePorCi($query, $ci)
    {
        return $query->where('ci_usuario', $ci);
    }

    /**
     * Scope para filtrar por módulo
     */
    public function scopePorModulo($query, $modulo)
    {
        return $query->where('modulo', $modulo);
    }

    /**
     * Scope para filtrar por acción
     */
    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para ordenar por más recientes
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Obtener descripción formateada con fecha
     */
    public function getDescripcionFormateadaAttribute(): string
    {
        $fecha = $this->created_at->format('d/m/Y H:i');
        return "[{$fecha}] {$this->descripcion}";
    }

    /**
     * Obtener resumen de cambios si hay datos anteriores y nuevos
     */
    public function getResumenCambiosAttribute(): ?array
    {
        if (!$this->datos_anteriores || !$this->datos_nuevos) {
            return null;
        }

        $cambios = [];
        foreach ($this->datos_nuevos as $campo => $valorNuevo) {
            $valorAnterior = $this->datos_anteriores[$campo] ?? null;
            if ($valorAnterior !== $valorNuevo) {
                $cambios[$campo] = [
                    'anterior' => $valorAnterior,
                    'nuevo' => $valorNuevo,
                ];
            }
        }

        return $cambios ?: null;
    }
}
