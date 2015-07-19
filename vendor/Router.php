<?php
/**
 * Класс маршрутизации.
 * 
 * @author     Мистер Х
 * @copyright  Copyright (c) 2015
 */

class Router {
    
	/**
	 * Дефолтный контроллер
     * 
	 * @var string
	 */
    const DEFAUL_CONTROLLER = 'Default';
    
	/**
	 * Деволтное действие контроллера
     * 
	 * @var string
	 */
    const DEFAUL_ACTION     = '';
    
	/**
	 * Префикс пространства имен
     * 
	 * @var string
	 */
    const NS_PREFIX         = '\\Controller';
    
	/**
	 * path_info
     * 
	 * @var string
	 */
    static private $path;
    
	/**
	 * Наши маршруты
     * 
	 * @var array
	 */
    static private $config = [];
    
	/**
	 * Пространство имен запрашиваемого контроллера
     * 
	 * @var string
	 */
    static private $namespace = '\\';
    
	/**
	 * Имя контроллера
     * 
	 * @var string
	 */
    static private $controller;
    
	/**
	 * Имя действия
     * 
	 * @var string
	 */
    static private $action;
    
	/**
	 * Аргументы
     * 
	 * @var array
	 */
    static private $args = [];
    
	/**
	 * Сборка конфигурации и инициализация маршрута
	 * 
	 * @param string $path
	 * @param array $config
	 * @return void
	 */
    static public function process($path, array $config)
    {
        self::$path = ltrim($path, '/');
        
        if (isset($config['global'])) {
            if (isset($config['namespaces'])) {
                foreach ($config['namespaces'] as &$namespace) {
                    $namespace = array_replace_recursive(
                    	$config['global'],
                        $namespace
                    );
                }
            }
            
            $config = array_replace_recursive(
            	$config['global'],
                $config
            );
            
            unset($config['global']);
        }
        
        self::$config = $config;
        
        self::init();
    }
    
	/**
	 * Инициализация маршрута
	 * 
	 * @return void
	 */
    static private function init()
    {
        if (self::find(self::$config)) {
            return;
        }
        
        if (isset(self::$config['namespaces'])) {
            foreach (self::$config['namespaces'] as $nsName => $nsConfig) {
                if (self::find($nsConfig, $nsName)) {
                    self::$namespace .=  ucfirst("{$nsName}\\");
                    
                    return;
                }
            }
        }
        
        throw new Exception('Path does not match any rule', 404);
    }
    
	/**
	 * Поиск маршрута
     * 
     * После нахождения маршрута можно будет пользоватся get... методами
	 * 
	 * @param array $nsConfig
	 * @param string $nsName
	 * @return void
	 */
    static private function find(array $nsConfig, $nsName = null)
    {
        if (!isset($nsConfig['rules'])) {
            return;
        }
        
        foreach ($nsConfig['rules'] as $rule) {
            $pattern = $rule['pattern'];
            
            $isRegex = false;
            
            if (!empty($pattern)) {
                if (isset($nsConfig['suffix'])) {
                    $pattern .= $nsConfig['suffix'];
                }
                
                if (isset($nsConfig['patterns'])
                    && (strpos($pattern, '{') !== false)
                ) {
                    foreach ($nsConfig['patterns'] as $key => $regex) {
                        $pattern = str_replace(
                        	"{{$key}}",
                            "(?P<{$key}>{$regex})",
                            $pattern
                        );
                    }
                    
                    $isRegex = true;
                }
                
                if ($nsName) {
                    $pattern = "{$nsName}/{$pattern}";
                }
            } else {
                $pattern = $nsName;
            }
            
            $isMatch = $isRegex
                ? preg_match("#^{$pattern}$#", self::$path, $matches)
                : ($pattern == self::$path);
            
            if ($isMatch) {
                if ($isRegex) {
                    foreach ($matches as $key => $value) {
                        if (!is_int($key)) {
                            self::$args[$key] = $value;
                        }
                    }
                }
                
                self::$controller = isset($rule['controller'])
                	? $rule['controller']
                    : self::DEFAUL_CONTROLLER;
                    
                self::$action = isset($rule['action'])
                	? $rule['action']
                    : self::DEFAUL_ACTION;
            
                return true;
            }
        }
    }
    
    static public function getArgs()
    {
        return self::$args;
    }
    
    static public function getController()
    {
        return self::$controller;
    }
    
    static public function getAction()
    {
        return self::$action;
    }
    
    static public function getNamespace()
    {
        return self::$namespace;
    }
    
    static public function getClass()
    {
        return self::NS_PREFIX . self::$namespace . self::$controller;
    }
    
    static public function getRule($name, $namespace = null)
    {
        if ($namespace) {
            return self::$config['namespaces'][$namespace]['rules'][$name];
        }
        
        return self::$config['rules'][$name];
    }
    
    static public function getSuffix($namespace = null)
    {
        if ($namespace) {
            if (isset(self::$config['namespaces'][$namespace]['suffix'])) {
                return self::$config['namespaces'][$namespace]['suffix'];
            }
            
            return '';
        }
        
        return isset(self::$config['suffix']) ? self::$config['suffix'] : '';
    }
}