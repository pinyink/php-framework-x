<?php

namespace Acme\Todo;

use React\Http\Message\Response;

class HelloController
{
    public function __invoke()
    {
        return Response::html(
            "<h1>Hello World</h1>"
        );
    }
}
