<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route()->parameter('usuario')?->id;
        $ignoreId = $usuarioId ? ",$usuarioId,id" : '';

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'ci' => ['required', 'string', 'max:20', "unique:usuarios,ci{$ignoreId}"],
            'fecha_nacimiento' => ['required', 'date_format:Y-m-d'],
            'genero_id' => ['nullable', 'uuid', 'exists:generos,id'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'max:150', "unique:usuarios,email{$ignoreId}"],
            'tipo_sangre_id' => ['nullable', 'uuid', 'exists:tipos_sangre,id'],
            'nivel_entrenamiento_id' => ['nullable', 'uuid', 'exists:niveles_entrenamiento,id'],
            'entidad_perteneciente' => ['nullable', 'string', 'max:200'],
            'rol_id' => ['nullable', 'uuid', 'exists:legacy_roles,id'],
            'debe_cambiar_password' => ['nullable', 'bool'],
            'reset_token' => ['nullable', 'string', 'max:255'],
            'reset_token_expires' => ['nullable', 'date_format:Y-m-d\TH:i:s'],
            'creado' => ['nullable', 'date_format:Y-m-d\TH:i:s'],
            'actualizado' => ['nullable', 'date_format:Y-m-d\TH:i:s'],
        ];
    }

    public function messages(): array
    {
        return [
            //'nombre' => '',
            //'apellido' => '',
            //'ci' => '',
            //'fecha_nacimiento' => '',
            //'genero_id' => '',
            //'telefono' => '',
            //'email' => '',
            //'tipo_sangre_id' => '',
            //'nivel_entrenamiento_id' => '',
            //'entidad_perteneciente' => '',
            //'rol_id' => '',
            //'debe_cambiar_password' => '',
            //'reset_token' => '',
            //'reset_token_expires' => '',
            //'creado' => '',
            //'actualizado' => '',
        ];
    }
}
