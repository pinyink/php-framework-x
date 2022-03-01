<?php

namespace Acme\Todo;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Loop;
use React\Http\Message\Response;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class AsyncUserController
{
    public function __invoke(ServerRequestInterface $request): \Generator
    {
        // async pseudo code to load some data from an external source
        $promise = $this->fetchRandomUserName();

        $name = yield $promise;
        assert(is_string($name));

        return Response::plaintext("Hello $name!\n");
    }

    /**
     * @return PromiseInterface<string>
     */
    private function fetchRandomUserName(): PromiseInterface
    {
        return new Promise(function ($resolve) {
            Loop::addTimer(0.01, function () use ($resolve) {
                $resolve('Alice');
            });
        });
    }
}
