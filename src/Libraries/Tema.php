<?php
namespace Acme\Todo\Libraries;

class Tema {
    
    public function render($path, $data = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader('../src/Views');
        $twig = new \Twig\Environment($loader, [
            'cache' => '../cache'
        ]);
        return $response = $twig->render($path, $data);
    }
}