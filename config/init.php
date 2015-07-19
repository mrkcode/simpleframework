<?php

/* -----------------------------------------------------------------------------
 * Assign constant BASE_PATH
 */

    define('BASE_PATH', realpath(__DIR__ . '/../'));


/* -----------------------------------------------------------------------------
 * Assign constant APP_PATH
 */

    define('APP_PATH', BASE_PATH . '/application');


/* -----------------------------------------------------------------------------
 * Adding include paths
 */

    set_include_path(implode(PATH_SEPARATOR, [
        BASE_PATH . '/vendor',
        APP_PATH
    ]));


/* -----------------------------------------------------------------------------
 * Setup time zone
 */

    date_default_timezone_set('Europe/Moscow');


/* -----------------------------------------------------------------------------
 * Setup locale and ecoding for string function
 */

    setlocale(LC_ALL, 'ru_RU.UTF-8');


/* -----------------------------------------------------------------------------
 * Setup encoding for mbstring functions
 */

    if (extension_loaded('mbstring')) {
        mb_internal_encoding('UTF-8');
    }
