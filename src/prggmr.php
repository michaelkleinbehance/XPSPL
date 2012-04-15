<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('PRGGMR_VERSION', '1.0.0-RC2');

// The creator
define('PRGGMR_MASTERMIND', 'Nickolas Whiting');

// dev mode
if (defined('PRGGMR_DEV_MODE')) {
    echo "RUNNING DEV MODE".PHP_EOL;
    error_reporting(E_ALL);
}

$dir = dirname(realpath(__FILE__));

// start'er up
require $dir.'/utils.php';
require $dir.'/storage.php';
require $dir.'/state.php';
require $dir.'/engine/signals.php';
require $dir.'/engine.php';
require $dir.'/signal/standard.php';
require $dir.'/signal.php';
require $dir.'/signal/complex.php';
require $dir.'/signal/time/time.php';
require $dir.'/signal/time/interval.php';
require $dir.'/event.php';
require $dir.'/queue.php';
require $dir.'/handle.php';
require $dir.'/api.php';

// debugging mode disabled by default
if (!defined('PRGGMR_DEBUG')) {
    define('PRGGMR_DEBUG', false);
}

// evented exceptions disabled by default
if (!defined('SIGNAL_ERRORS_EXCEPTIONS')) {
    define('SIGNAL_ERRORS_EXCEPTIONS', false);
}
/**
 * The prggmr object works as the global instance used for managing the
 * global api.
 */
final class prggmr extends \prggmr\Engine {

    /**
     * @var  object|null  Instanceof the singleton
     */
    private static $_instance = null;

    /**
     * Returns instance of the prggmr api.
     */
    final public static function instance(/* ... */)
    {
        if (null === static::$_instance) {
            static::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Returns the current version of prggmr.
     *
     * @return  string
     */
    final public static function version(/* ... */)
    {
        return PRGGMR_VERSION;
    }
}

/**
 * Enables prggmr to transform any errors and exceptions into a 
 * catchable signal.
 */
if (SIGNAL_ERRORS_EXCEPTIONS === true) {
    set_error_handler("signal_exceptions");
    set_exception_handler("signal_errors");
}
