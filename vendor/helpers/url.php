<?php

/* -----------------------------------------------------------------------------
 * HTTP protocol
 */

    if (!defined('URL_PROTOCOL')) {
        $protocol = strtolower($_SERVER['SERVER_PROTOCOL']);
        
        $url_protocol = substr($protocol, 0, strpos($protocol, '/'));
        
        define('URL_PROTOCOL', $url_protocol);
        
        unset($protocol, $url_protocol);
    }


/* -----------------------------------------------------------------------------
 * HTTP hostname
 */

    if (!defined('URL_HOST')) {
        if (isset($_SERVER['HTTP_HOST'])) {
        	$host = $_SERVER['HTTP_HOST'];
        } else {
        	$host = $_SERVER['SERVER_NAME'];
        }
        
        define('URL_HOST', $host);
        
        unset($host);
    }


/* -----------------------------------------------------------------------------
 * Url folder
 */

    if (!defined('URL_FOLDER')) {
        $script_name = $_SERVER['SCRIPT_NAME'];
        $url_folder  = substr($script_name, 0, strrpos($script_name, '/') + 1);

        define('URL_FOLDER', $url_folder);
        
        unset($script_name, $url_folder);
    }


/* -----------------------------------------------------------------------------
 * Base URL
 */

    if (!defined('BASE_URL')) {
        $base_url = URL_PROTOCOL . '://' . URL_HOST . URL_FOLDER;
        
        define('BASE_URL', $base_url);
        
        unset($base_url);
    }


/* -----------------------------------------------------------------------------
 * Url path info
 */

    if (!defined('URL_PATH_INFO')) {
        if (isset($_SERVER['PATH_INFO'])) {
        	echo $path_info = $_SERVER['PATH_INFO'];
        }  else {
        	$uri       = urldecode($_SERVER['REQUEST_URI']);
        	$path      = parse_url($uri, PHP_URL_PATH);
        	$path_info = substr($path, strlen(URL_FOLDER) - 1);
        }
        
        define('URL_PATH_INFO', $path_info);
        
        unset($uri, $path, $path_info);
    }


/* -----------------------------------------------------------------------------
 * Is HTTP POST request
 */

    if (!defined('IS_POST')) {
        $is_post = strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
        
        define('IS_POST', $is_post);
        
        unset($is_post);
    }


/* -----------------------------------------------------------------------------
 * Is ajax request
 */

    if (!defined('IS_AJAX')) {
        $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        define('IS_AJAX', $is_ajax);
        
        unset($is_ajax);
    }


/* -----------------------------------------------------------------------------
 * Create URI
 */

    if (!function_exists('route')) {
        function route($name, array $args = []) {
            if (strpos($name, ':')) {
                list($namespace, $ruleName) = explode(':', $name, 2);
            } else {
                $namespace = '';
                $ruleName  = $name;
            }
            
            $rule = Router::getRule($ruleName, $namespace);
            
            $uri = $rule['pattern'];
            
            if ($uri) {
                $uri .= Router::getSuffix($namespace);
                
                foreach ($args as $argName => $argValue) {
                    $uri = str_replace("{{$argName}}", $argValue, $uri);
                }
                
                if ($namespace) {
                    $uri = "{$namespace}/{$uri}";
                }
            } else {
                $uri = $namespace;
            }
            
            return $uri;
        }
    }


/* -----------------------------------------------------------------------------
 * Create URL by rule
 */

    if (!function_exists('url')) {
        function url($name, array $args = [], array $queryArray = []) {
           $url = BASE_URL . route($name, $args);
            
            if ($queryArray) {
                $url .= '?' . http_build_query($queryArray, '', '&amp;');
            }
            
            return $url;
        }
    }
