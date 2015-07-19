<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Compress;

use Exception;

/**
 * Description of Css
 *
 * @author Алексей
 */
abstract class Compresser {
    private $body = '';
    private $bodyIsMinified = false;
    private $tempFile;
    private $is = false;
    
    protected $mninify = [];
    protected $lifeTime;
    
    public function __construct($tempFile = null)
    {
        $this->tempFile = $tempFile;
        
        $this->is = is_file($tempFile)
            && (!$this->lifeTime || (filemtime($tempFile) + $this->lifeTime) > time());
    }
    
    public function tempFile()
    {
        if (!$this->is) {
            $this->createFile();
        }
        
        return $this->tempFile;
    }
    
    public function add($str)
    {
        if ($this->is) {
            return;
        }
        
        if (is_file($str)) {
            $this->body .= file_get_contents($str);
        } else {
            $this->body .= $str;
        }
        
        $this->bodyIsMinified = false;
    }
    
    private function createFile()
    {
        if (!$this->tempFile) {
        	throw new Exception('You should specify tempFile in ' . get_class($this) . '::__construct().');
        }
        
        file_put_contents($this->tempFile, $this->__toString());
        
        $this->is = true;
    }
    
    public function __toString()
    {
        if (!empty($this->minify) && !$this->bodyIsMinified) {
            $this->body = preg_replace(
                array_keys($this->minify),
                array_values($this->minify),
                trim($this->body)
            );
            
            $this->bodyIsMinified = true;
        }
        
         return $this->body;
    }
}
