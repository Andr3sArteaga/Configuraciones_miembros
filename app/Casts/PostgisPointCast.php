<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class PostgisPointCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        // Si el valor ya es un array, retornarlo directamente
        if (is_array($value)) {
            return $value;
        }

        // Parsear el formato WKT (Well-Known Text) de PostGIS
        // Ejemplo: "POINT(-74.006 40.7128)"
        if (is_string($value) && str_starts_with($value, 'POINT(')) {
            $coordinates = trim(substr($value, 6, -1)); // Remover "POINT(" y ")"
            $parts = explode(' ', $coordinates);
            
            if (count($parts) === 2) {
                return [
                    'longitude' => (float) $parts[0],
                    'latitude' => (float) $parts[1]
                ];
            }
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        // Si es un array con latitud y longitud, convertirlo a formato WKT
        if (is_array($value) && isset($value['latitude']) && isset($value['longitude'])) {
            return "POINT({$value['longitude']} {$value['latitude']})";
        }

        // Si es un string en formato WKT, validarlo y retornarlo
        if (is_string($value) && str_starts_with($value, 'POINT(')) {
            return $value;
        }

        // Si es un string con coordenadas separadas por coma
        if (is_string($value) && str_contains($value, ',')) {
            $coordinates = explode(',', $value);
            if (count($coordinates) === 2) {
                $longitude = trim($coordinates[0]);
                $latitude = trim($coordinates[1]);
                return "POINT({$longitude} {$latitude})";
            }
        }

        throw new InvalidArgumentException(
            'El valor debe ser un array con latitud y longitud, o un string en formato WKT POINT'
        );
    }
}
