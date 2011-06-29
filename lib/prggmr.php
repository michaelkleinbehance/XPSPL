<?php
/**
 *  Copyright 2010 Nickolas Whiting
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 *
 * @author  Nickolas Whiting  <me@nwhiting.com>
 * @package  prggmr
 * @copyright  Copyright (c), 2010 Nickolas Whiting
 */

define('PRGGMR_VERSION', '0.2.0');

$dir = dirname(realpath(__FILE__));

// start'er up
require $dir.'/engine.php';
require $dir.'/signalinterface.php';
require $dir.'/signal.php';
require $dir.'/regexsignal.php';
require $dir.'/event.php';
require $dir.'/api.php';
require $dir.'/queue.php';
require $dir.'/subscription.php';

/**
 * The prggmr object is a singleton which allows for a global engine api.
 */
class Prggmr extends \prggmr\Engine {

	/**
     * @var  object|null  Instanceof the singleton
     */
    private static $_instance = null;

	/**
     * Returns instance of the Prggmr api.
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