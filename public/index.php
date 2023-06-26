<?php

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';

$credentials = ':root@localhost/fm_x?idle=0.001';
$db = (new React\MySQL\Factory())->createLazyConnection($credentials);

$app = new FrameworkX\App();

$app->get('/', new Acme\Todo\HelloController());
$app->get('/user/{name}', new Acme\Todo\UserController());
$app->get('/users', new Acme\Todo\AsyncContentTypeMiddleware(), new Acme\Todo\AsyncUserController());
$app->get('/crud', function() {
    $controller = new Acme\Todo\Controllers\CrudController();
    return $controller->view();
});

$app->run();
