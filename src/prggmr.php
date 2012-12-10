<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('PRGGMR_VERSION', '2.1.0');

// The creator
define('PRGGMR_MASTERMIND', 'Nickolas Whiting');

// Path relative to this file
define('PRGGMR_PATH', dirname(realpath(__FILE__)));
set_include_path(PRGGMR_PATH.'/../../' . PATH_SEPARATOR . get_include_path());

// start'er up
// utils & traits
require PRGGMR_PATH.'/utils.php';
require PRGGMR_PATH.'/api.php';

// debugging mode disabled by default
if (!defined('PRGGMR_DEBUG')) {
    define('PRGGMR_DEBUG', false);
}

// have the engine throw exceptions on signal errors
if (!defined('PRGGMR_ENGINE_EXCEPTIONS')) {
    define('PRGGMR_ENGINE_EXCEPTIONS', true);
}

// event history original setting
if (!defined('PRGGMR_EVENT_HISTORY')) {
    define('PRGGMR_EVENT_HISTORY', false);
}

// dev mode
if (PRGGMR_DEBUG) {
    error_reporting(E_ALL);
    define('SIGNAL_ERRORS_EXCEPTIONS', true);
}

// evented exceptions disabled by default
if (!defined('SIGNAL_ERRORS_EXCEPTIONS')) {
    define('SIGNAL_ERRORS_EXCEPTIONS', false);
}

// immediatly removes exhausted handles from the engine
if (!defined('PRGGMR_PURGE_EXHAUSTED')) {
    define('PRGGMR_PURGE_EXHAUSTED', true);
}

// modules directory
if (!defined('PRGGMR_MODULE_DIR')) {
    define('PRGGMR_MODULE_DIR', PRGGMR_PATH . '/../module');
}
/**
 * The prggmr object works as the global instance used for managing the
 * global engine instance.
 */
final class prggmr extends \prggmr\Engine {

    use prggmr\Singleton;

    /**
     * Initialise the global engine instance.
     *
     * @param  boolean  $event_history  Store a history of all events.
     * @param  boolean  $engine_exceptions  Throw an exception when a error 
     *                                      signal is triggered.
     * 
     * @return  object  prggmr\Engine
     */
    final public static function init($event_history = true, $engine_exceptions = true) 
    {
        if (null === static::$_instance) {
            static::$_instance = new self($event_history, $engine_exceptions);
        }
        return static::$_instance;
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
 * Start the engine VROOOOOOM!
 */
prggmr::init(PRGGMR_EVENT_HISTORY, PRGGMR_ENGINE_EXCEPTIONS);

/**
 * Enables prggmr to transform any errors and exceptions into a 
 * catchable signal.
 */
if (SIGNAL_ERRORS_EXCEPTIONS === true) {
    set_error_handler("signal_exceptions");
    set_exception_handler("signal_errors");
}