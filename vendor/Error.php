<?php

class Error {
    static private $statusMessages = array(
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    
    static private $phpErrorTypes = array(
        E_ERROR              => 'Fatal Error',
        E_USER_ERROR         => 'User Error',
        E_PARSE              => 'Parse Error',
        E_WARNING            => 'Warning',
        E_USER_WARNING       => 'User Warning',
        E_STRICT             => 'Strict',
        E_NOTICE             => 'Notice',
        E_RECOVERABLE_ERROR  => 'Recoverable Error',
        E_DEPRECATED         => 'Deprecated',
    );
    
    static private $config = [];
    static private $init   = false;
    
    static public function initListener(array $config = [])
    {
        if (!self::$init) {
            self::$init = true;
            
            ini_set('display_errors', 'Off');
            ini_set('error_reporting', E_ALL);
            
            self::$config = $config;
            
            register_shutdown_function(array(__CLASS__, 'listener'));
            set_exception_handler(array(__CLASS__, 'listener'));
            set_error_handler(array(__CLASS__, 'listener'));
            
            ob_start();
        }
    }
    
    static private function getPhpTypeError($errno)
    {
        $type = 'Unknown';
        
        if (isset(self::$phpErrorTypes[$errno])) {
            $type = self::$phpErrorTypes[$errno];
        }
        
        return $type;
    }
    
    static private function getStatusMessage($code)
    {
        if (!isset(self::$statusMessages[$code])) {
            echo "<p>Unknown status message by code: {$code}.</p><hr>";
            
            $code = 500;
        }
        
        return $code . ' ' . self::$statusMessages[$code];
    }
    
    static public function listener($exception = null)
    {
        if (!is_a($exception, 'Exception')) {
            if (!($error = func_get_args())
                && !($error = array_values((array)error_get_last()))
            ) {
                return;
            }
            
            $status  = 500;
            $type    = self::getPhpTypeError($error[0]);
            $message = $error[1];
            $file    = $error[2];
            $line    = $error[3];
            $trace   = debug_backtrace();
        } else {
            $status  = $exception->getCode();
            $type    = get_class($exception);
            $message = $exception->getMessage();
            $file    = $exception->getFile();
            $line    = $exception->getLine();
            $trace   = $exception->getTrace();
        }
        
        ob_get_level() && ob_end_clean();
        
        if (isset(self::$config['logger'])) {
            if (is_callable(self::$config['logger'])) {
                call_user_func_array(self::$config['logger'], [
                	$type,
                    $message,
                    $file,
                    $line
                ]);
            } else {
                echo "<p>Logger isn't callable.</p><hr>";
            }
        }
        
        header(
        	$_SERVER['SERVER_PROTOCOL'] . ' ' . self::getStatusMessage($status)
        );
        
        $mode = isset(self::$config['mode']) ? self::$config['mode'] : 'debug';
        
        switch ($mode) {
            case 'production':
                self::productionProcess(
    				$status,
        			$type,
       				$message,
       				$file,
        			$line,
        			$trace
    			);
                break;
            case 'debug':
                self::debugProcess(
    				$status,
        			$type,
       				$message,
       				$file,
        			$line,
        			$trace
    			);
                break;
            default:
                echo '<p>Incorrect error mode.</p><hr>';
        }
        
        die;
    }
    
    static private function productionProcess(
    	$status,
        $type,
        $message,
        $file,
        $line,
        $trace
    ) {
        if (isset(self::$config['status_tpls'][$status])) {
            if (is_file(self::$config['status_tpls'][$status])) {
                include self::$config['status_tpls'][$status];
                
                return;
            } else {
                echo
                	'<p>Undefined status template: ' .
                	self::$config['status_tpls'][$status] . '.</p><hr>';
            }
        }
        
        echo
        	'<h1>[' . $status . '] ' .
        	self::$statusMessages[$status] . '</h1><hr>';
    }
    
    
    static private function debugProcess(
    	$status,
        $type,
        $message,
        $file,
        $line,
        $trace
    ) {
        if (isset(self::$config['debug_tpl'])) {
            if (is_file(self::$config['debug_tpl'])) {
                include self::$config['debug_tpl'];
                
                return;
            } else {
                echo
                	'<p>Undefined debug template: ' .
                	self::$config['debug_tpl'] . '.</p><hr>';
            }
        }
        
        echo
        	"<p>HTTP Status: {$status}<hr><h2>[{$type}]</h2>{$message} " .
            "in {$file}<strong>:{$line}</strong></p>";
    }
}