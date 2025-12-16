<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RemoteVoluntariosCursosService
{
    public function syncCurso($data)
    {
        return Http::withToken(env('VOLUNTARIOS_TOKEN'))
            ->post(env('VOLUNTARIOS_URL') . '/sync/cursos', $data);
    }
}

