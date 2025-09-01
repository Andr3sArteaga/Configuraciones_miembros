<?php

namespace App\GraphQL\Queries;

class Hello
{
    public function resolve($_, array $args)
    {
        return 'Hola desde GraphQL!';
    }
}
