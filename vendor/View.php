<?php

class View {
    private $dir;
    private $ext;
    private $vars = [];
    private $tplFile;
    
    public function __construct($tpl)
    {
        $pathInfo = pathinfo($tpl);
        
        $this->tplName = $pathInfo['filename'];
        $this->dir     = $pathInfo['dirname'];
        $this->ext     = $pathInfo['extension'];;
    }
    
    private function extend($tpl = null)
    {
        static $parent;
        
        if ($tpl) {
            $parent = $tpl;
        } else {
            $tpl = $parent;

            $parent = null;

            return $tpl;
        }
    }
    
    private function content($tpl = null)
    {
        static $data, $lock = false;
        
        if (!$lock && $tpl) {
            $lock = true;
            
            $data = $this->render($tpl);
            
            $lock = false;
        
        } else {
            $str = $data;

            $data = null;
            
            return $str;
        }
    }
    
    public function display()
    {
        echo $this->__toString();
    }
    
    public function __toString()
    {
        $this->content($this->tplName);
        
        while ($parent = $this->extend()) {
            $this->content($parent);
        }
        
        return $this->content();
    }
    
    public function vars()
    {
        switch (func_num_args()) {
            case 1:
            	if (is_array(func_get_arg(0))) {
             		$this->vars = func_get_arg(0);
            	} else {
                	return isset($this->vars[func_get_arg(0)])
                		? $this->vars[func_get_arg(0)]
                    	: null;
           		}
            	break;
            case 2:
            	$this->vars[func_get_arg(0)] = func_get_arg(1);
            	break;
            default:
            	return $this->vars;
        }
    }
    
    public function render($tplName)
    {
        extract($this->vars);
        
        ob_start();
        
        require "{$this->dir}/{$tplName}.{$this->ext}";
        
        return ob_get_clean();
    }
}