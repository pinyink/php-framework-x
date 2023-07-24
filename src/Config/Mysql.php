<?php

namespace Acme\Todo\Config;

class mysql
{
    private $credentials = 'alice:secret@localhost/bookstore?idle=0.001';
    
    public function getCredentials()
    {
        return $this->credentials;
    }
}