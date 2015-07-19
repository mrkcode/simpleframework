<?php

namespace Controller;

use View;
use Compress\CssCompress;
use Compress\HtmlCompress;

abstract class Base extends \Controller {
    public function _before()
    {
        
    }
    
    public function _after()
    {
        
    }
    
    protected function redirect($name, array $args = [])
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
        header('Location: ' . route($name, $args));
        die;
    }
}