<?php

namespace Acme\Todo;

use React\Http\Message\Response;

class HelloController
{
    public function __invoke()
    {
        // $g = $_COOKIE['cobacookie'];
        $dir = __DIR__.'/../';
        return Response::html(
            "<h1>".$dir."</h1>"
        );
    }
}
