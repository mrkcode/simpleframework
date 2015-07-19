<?php

return [
    'mode'      => 'debug',
    //'debug_tpl' => BASE_PATH . '/error/debug.php',
    'status_tpls' => [
        404 => BASE_PATH . '/error/404.php',
    ],
    
    'logger' => 'write_error_log'
];