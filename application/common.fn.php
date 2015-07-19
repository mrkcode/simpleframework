<?php

/* -----------------------------------------------------------------------------
 * Error logger
 */

	function write_error_log($type, $message, $file, $line)
    {
        file_put_contents(
            APP_PATH . '/log/error.log',
            date('Y-m-d H:i:s') . ' [' . $_SERVER['REMOTE_ADDR'] . '] ' .
            	"[{$type}] {$message} in {$file}:{$line}\n",
            FILE_APPEND
        );
    }

	function login_user($login, $password)
    {
        
    }
    
    function login_admin($login, $password)
    {
        return [
            [],
            ['admin_id' => 1]
        ];
    }