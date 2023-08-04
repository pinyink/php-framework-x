<?php

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';

$container = new FrameworkX\Container([
    React\MySQL\ConnectionInterface::class => function () {
        $credentials = 'root:@localhost/fm_x?idle=0.001';
        return (new React\MySQL\Factory())->createLazyConnection($credentials);
    }
]);

$app = new FrameworkX\App($container);

$app->get('/', Acme\Todo\HelloController::class);
$app->get('/user/{id}', Acme\Todo\Controllers\UserController::class);
$app->get('/crud', Acme\Todo\Controllers\Crud\CrudController::class);
$app->post('/crud/insert', Acme\Todo\Controllers\Crud\CrudInsertController::class);
$app->post('/crud/datatable', Acme\Todo\Controllers\Crud\CrudDatatableController::class);
$app->get('/crud/get/{id}', Acme\Todo\Controllers\Crud\CrudGetController::class);

$app->run();
