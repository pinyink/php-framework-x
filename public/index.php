<?php

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../framework-x/vendor/autoload.php';
$app = new FrameworkX\App();

$app->get('/', new Acme\Todo\HelloController());
$app->get('/users/{name}', new Acme\Todo\UserController());
$app->get('/user', new Acme\Todo\AsyncContentTypeMiddleware(), new Acme\Todo\AsyncUserController());

$app->run();
