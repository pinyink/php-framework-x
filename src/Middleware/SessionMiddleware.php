<?php

namespace Acme\Todo\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class SessionMiddleware 
{

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request);
    }
}