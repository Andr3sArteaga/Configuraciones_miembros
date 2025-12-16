<?php

namespace App\Listeners;

use App\Events\CursoCreadoEnIncendios;
use App\Services\RemoteVoluntariosCursosService;

class EnviarCursoAVoluntarios
{
    public function handle(CursoCreadoEnIncendios $event)
    {
        $curso = $event->curso;

        app(RemoteVoluntariosCursosService::class)->syncCurso([
            'nombre' => $curso->nombre,
            'descripcion' => $curso->descripcion
        ]);
    }
}
