<?php
namespace Acme\Todo\Libraries;

class Tema {
    
    public function render($path, $data = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Views');
        $twig = new \Twig\Environment($loader, [
            'cache' => '../cache',
            'auto_reload' => 1
        ]);
        return $response = $twig->render($path, $data);
    }
}