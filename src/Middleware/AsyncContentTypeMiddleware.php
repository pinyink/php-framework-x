<?php

namespace Acme\Todo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class AsyncContentTypeMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $response = $next($request);

        if ($response instanceof PromiseInterface) {
            return $response->then(fn (ResponseInterface $response) => $this->handle($response));
        } elseif ($response instanceof \Generator) {
            return (fn () => $this->handle(yield from $response))();
        } else {
            return $this->handle($response);
        }
    }

    private function handle(ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Content-Type', 'text/plain');
    }
}
