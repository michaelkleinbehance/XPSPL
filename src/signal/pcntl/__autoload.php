<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */
if (!function_exists('pcntl_signal')) {
    throw new RuntimeException(
        'pcntl signal library requires the pcntl module to be loaded'
    );
}

/**
 * Autoloads the pcntl signal library.
 */

$dir = dirname(realpath(__FILE__));
require_once $dir.'/signal.php';
require_once $dir.'/interrupt.php';
require_once $dir.'/terminate.php';
require_once $dir.'/api.php';