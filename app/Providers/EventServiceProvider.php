<?php

namespace App\Providers;

use App\Events\CursoCreadoEnIncendios;
use App\Listeners\EnviarCursoAVoluntarios;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // 👇 agrega esto
        CursoCreadoEnIncendios::class => [
            EnviarCursoAVoluntarios::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
