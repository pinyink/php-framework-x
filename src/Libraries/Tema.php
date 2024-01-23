<?php
namespace Acme\Todo\Libraries;

class Tema 
{
    private $loader;
    
    function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Views');
    }
    
    public function render($path, $data = [])
    {
        $twig = new \Twig\Environment($this->loader, [
            'cache' => '../cache',
            'auto_reload' => 1
        ]);
        return $response = $twig->render($path, $data);
    }
}