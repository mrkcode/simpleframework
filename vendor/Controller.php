<?php

class Controller {
    private $args = [];
    
    static private $called = [];
    
    public function __construct($args) {
        $this->args = $args;
    }
    
    static public function run(array $callArgs) {
        if (count($callArgs) < 2) {
            throw new Exception('Incorect argument $callArgs', 500);
        }
        
        if (count($callArgs) < 3) {
            $callArgs[] = [];
        }
        
        list($controller, $action, $args) = $callArgs;
        
        if(is_object($controller)) {
            $className = get_class($controller);
        } else {
            $className = $controller;
        }
        
        if (!isset(self::$called[$className])) {
            if (!is_object($controller)) {
                $controller = new $className((array)$args);
            }
            
            self::$called[$className] = $controller;
            
            if (!is_a($controller, __CLASS__)) {
                throw new Exception("{$className} must inherit " . __CLASS__);
            }
            
            $call = $controller->_before();
            
            if ($call) {
                self::run($call);
                
                return;
            }
        }
        
        $call = call_user_func(array(self::$called[$className], "{$action}Action"));
        
        if ($call) {
            self::run($call);
            
            return;
        }
        
        self::$called[$className]->_after();
    }
    
    protected function args($key = null) {
        if ($key) {
             return isset($this->args[$key]) ? $this->args[$key] : null;
        }
        
        return $this->args;
    }
    
    public function _before() {
    }
    
    public function _after() {
    }
}