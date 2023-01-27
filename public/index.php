<?php

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';

$credentials = ':root@localhost/fm_x?idle=0.001';
$db = (new React\MySQL\Factory())->createLazyConnection($credentials);

$app = new FrameworkX\App();

$app->get('/', new Acme\Todo\HelloController());
$app->get('/users/{name}', new Acme\Todo\UserController());
$app->get('/user', new Acme\Todo\AsyncContentTypeMiddleware(), new Acme\Todo\AsyncUserController());

$app->run();
