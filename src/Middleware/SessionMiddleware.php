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
        // return $next($request);

        // if false
        // return Response::json([
        //     'error' => 'Anda Belum Login'
        // ]);

        // Init our repositories
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface

        // Path to public and private keys
        $privateKey = 'file://'.__DIR__.'../private.key';
        //$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase
        $encryptionKey = 'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'; // generate using base64_encode(random_bytes(32))

        // Setup the authorization server
        $server = new \League\OAuth2\Server\AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptionKey
        );

        // Enable the client credentials grant on the server
        $server->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }
}