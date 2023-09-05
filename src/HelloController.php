<?php

namespace Acme\Todo;

use React\Http\Message\Response;

class HelloController
{
    public function __invoke()
    {
        $g = $_COOKIE['cobacookie'];
        return Response::html(
            "<h1>".$g."</h1>"
        );
    }
}
