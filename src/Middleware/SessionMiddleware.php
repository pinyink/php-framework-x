<?php

namespace Acme\Todo\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface as ClientRepository;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface as ScopeRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as AccessTokenRepository;

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