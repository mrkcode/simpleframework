<?php

/* -----------------------------------------------------------------------------
 * Include initialization config file
 */

    require 'config/init.php';


/* -----------------------------------------------------------------------------
 * Include helpers constant and functions
 */

    require 'helpers/common.php';
    require 'helpers/url.php';

// -----------------------------------------------------------------------------
    require APP_PATH . '/common.fn.php';


/* -----------------------------------------------------------------------------
 * Assign autoloader
 */

    spl_autoload_register('simple_autoload');


/* -----------------------------------------------------------------------------
 * Setup error listener
 */

    Error::initListener(include 'config/error.php');


/* -----------------------------------------------------------------------------
 * Finding route
 */

    Router::process(
        URL_PATH_INFO,
        include 'config/routing.php'
    );


/* -----------------------------------------------------------------------------
 * Run requested action
 */

    Controller::run([
        Router::getClass(),
        Router::getAction(),
        Router::getArgs()
    ]);
