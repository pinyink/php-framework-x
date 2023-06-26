<?php

namespace Acme\Todo\Controllers;

use React\Http\Message\Response;

class CrudController 
{
    public function __invoke()
    {
        return Response::html('<h1>Hello Crud</h1>');
    }

    public function view()
    {
        return Response::html('<h1>Try View Your Future</h1>');
    }
}