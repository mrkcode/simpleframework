<?php

/* -----------------------------------------------------------------------------
 * Simple autoload
 */

    if (!function_exists('simple_autoload')) {
        function simple_autoload($className) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            $file = stream_resolve_include_path($file);
            
            if ($file) {
                include $file;
                
                return true;
            }
            
            return false;
        }
    }


/* -----------------------------------------------------------------------------
 * Escaped HTML
 */

    if (function_exists('escape_html')) {
        function escape_html($str) {
            return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
        }
    }
