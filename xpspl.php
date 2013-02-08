<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('XPSPL_VERSION', '4.0.0');

// The creator
define('XPSPL_MASTERMIND', 'Nickolas C. Whiting');

// Add this to include path
if (!defined('XPSPL_PATH')) {
    define('XPSPL_PATH', dirname(realpath(__FILE__)));
}
set_include_path(
    XPSPL_PATH . '/module' . PATH_SEPARATOR .
    XPSPL_PATH . '/..' . PATH_SEPARATOR . 
    get_include_path()
);
// start'er up
// utils & traits
require XPSPL_PATH.'/src/utils.php';
require XPSPL_PATH.'/src/const.php';
require XPSPL_PATH.'/src/api.php';

// Load the API
// believe it or not this is the fastest way to do this
$dir = new \RegexIterator(
    new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator(XPSPL_PATH.'/api')
    ), '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH
);
foreach ($dir as $_file) {
    array_map(function($i){
        require_once $i;
    }, $_file);
}

// dev mode
if (XPSPL_DEBUG) {
    define('LOGGER_DATE_FORMAT', 'm-d-y H:i:s');
    error_reporting(E_ALL);
    import('logger');
    $log = logger(XPSPL_LOG);
    $formatter = new Formatter(
        '[{date}] [{str_code}] {message}'.PHP_EOL
    );
    $log->add_handler(new Handler(
        $formatter, STDOUT
    ));
}

/**
 * XPSPL
 * 
 * XPSPL is a globally available singleton used for communication access via the 
 * API.
 */
final class XPSPL extends \XPSPL\Processor {
    use XPSPL\Singleton;
}

/**
 * Start the processor VROOOOOOM!
 */
set_signal_history(XPSPL_SIGNAL_HISTORY);