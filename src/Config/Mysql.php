<?php

namespace Acme\Todo\Config;

class mysql
{
    private $credentials = 'root:@localhost/fm_x?idle=0.001';
    
    public function getCredentials()
    {
        return $this->credentials;
    }
}