<?php

namespace Acme\Todo\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class SessionMiddleware 
{

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        // if true
        return $next($request);

        // if false
        // return Response::json([
        //     'error' => 'Anda Belum Login'
        // ]);
    }
}