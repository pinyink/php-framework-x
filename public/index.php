<?php

use Acme\Todo\Config\AppConfig;

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';

define('PUBLICPATH', realpath( __DIR__. '../../public') . DIRECTORY_SEPARATOR);

$container = new FrameworkX\Container([
    React\MySQL\ConnectionInterface::class => function () {
        $config = new AppConfig();
        return (new React\MySQL\Factory())->createLazyConnection($config->credentials);
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

$app->get('/login', Acme\Todo\Controllers\LoginController::class);
$app->post('/login_action', Acme\Todo\Controllers\LoginActionController::class);

$app->run();
