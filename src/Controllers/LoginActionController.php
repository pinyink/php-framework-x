<?php

namespace Acme\Todo\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class LoginActionController
{
    public function __invoke(ServerRequestInterface $request) : Response
    {
        $data = $request->getParsedBody();
        return Response::json($data);
    }
}