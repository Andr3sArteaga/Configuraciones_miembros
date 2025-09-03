<?php

namespace App\GraphQL\Queries;

class Usuario
{
    public function resolve($_, array $args)
    {
        return 'Hola usuario desde GraphQL!';
    }
}
