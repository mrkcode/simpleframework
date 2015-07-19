<?php

namespace Controller;

use View;
use Compress\CssCompress;
use Compress\HtmlCompress;

class Home extends \Controller {
    public function Action()
    {
        $view = new View('view/home.php');
        
        $tempFile = 'temp/' . md5(get_class($this))  . '.css';
        
        $view->vars([
            'css' => new CssCompress($tempFile, 1),
            'uri' => route('admin:editArticle', ['id' => 3])
        ]);
        
        $compressor = new HtmlCompress;
        $compressor->add($view);
        
        echo $compressor;
    }
    
    public function viewArticleAction()
    {
        echo __METHOD__;
    }
}