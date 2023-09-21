<?php

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface as ClientRepository;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface as ScopeRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as AccessTokenRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';

$container = new FrameworkX\Container([
    React\MySQL\ConnectionInterface::class => function () {
        $credentials = 'root:@localhost/fm_x?idle=0.001';
        return (new React\MySQL\Factory())->createLazyConnection($credentials);
    },
    'X_LISTEN' => fn(string $PORT = '8000') => '0.0.0.0:' . $PORT,
]);

$app = new FrameworkX\App($container);

$app->get('/', Acme\Todo\HelloController::class);
$app->get('/user/{id}', Acme\Todo\Controllers\UserController::class);
$app->get('/crud', Acme\Todo\Middleware\SessionMiddleware::class, Acme\Todo\Controllers\Crud\CrudController::class);
$app->post('/crud/insert', Acme\Todo\Controllers\Crud\CrudInsertController::class);
$app->post('/crud/datatable', Acme\Todo\Controllers\Crud\CrudDatatableController::class);
$app->get('/crud/get/{id}', Acme\Todo\Controllers\Crud\CrudGetController::class);

$app->run();
