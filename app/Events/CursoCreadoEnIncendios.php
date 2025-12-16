<?php

namespace App\Events;

use App\Models\Curso;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CursoCreadoEnIncendios
{
    use Dispatchable, SerializesModels;

    public $curso;

    public function __construct(Curso $curso)
    {
        $this->curso = $curso;
    }
}



