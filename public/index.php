<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new FrameworkX\App();

$app->get('/', new Acme\Todo\HelloController());
$app->get('/users/{name}', new Acme\Todo\UserController());

$app->run();
