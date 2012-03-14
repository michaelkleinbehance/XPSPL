<?php
/**
 *  Copyright 2010-12 Nickolas Whiting
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
 * @author  Nickolas Whiting  <prggmr@gmail.com>
 * @package  prggmr
 * @copyright  Copyright (c), 2010-12 Nickolas Whiting
 */

/**
 * Returns the current UNIX timestamp in milliseconds.
 * 
 * @return  integer
 */
function get_milliseconds(/* ... */) {
    return int(round(microtime(true) * 1000));
}

/**
 * Transforms PHP exceptions into a signal.
 * 
 * The signal fired is \prggmr\engine\Signal::GLOBAL_EXCEPTION
 * 
 * @param  object  $exception  \Exception
 * 
 * @return void
 */
function signal_exceptions($exception) {
    signal(\prggmr\engine\Signal::GLOBAL_EXCEPTION, $exception);
}

/**
 * Transforms PHP errors into a signal.
 * 
 * The signal fired is \prggmr\engine\Signal::GLOBAL_ERROR
 * 
 * @param  int  $errno
 * @param  string  $errstr
 * @param  string  $errfile
 * @param  int  $errline
 * 
 * @return  void
 */
function signal_errors($errno, $errstr, $errfile, $errline) {
    signal(\prggmr\engine\Signal::GLOBAL_ERROR, array(
        $errstr, 0, $errno, $errfile, $errline
    ));
}

/**
 * Performs a binary search for the given node returning the index.
 * 
 * @param  mixed  $needle  Needle to locate
 * @param  array  $haystack  Array to search
 * @param  closure  $compare  Comparison function
 * 
 * @return  int  > 0 if found
 */
function bin_search($needle, $haystack, $compare = null)
{

    if (null === $compare) {
        $compare = function($node, $needle) {
            if ($node < $needle) {
                return 0;
            }
            if ($node > $needle) {
                return 1;
            }
            if ($node === $needle) {
                return true;
            }
        };
    }

    $low = 0;
    $high = count($haystack) - 1;
    
    while ($low <= $high) {
        $mid = ($low + $high) >> 1;
        $node = $hackstack[$mid];
        $cmp = $compare($node, $needle);
        switch (true) {
            case $cmp === true:
                return $mid;
                break;
            case $cmp === 0:
                $low = $mid + 1;
                break;
            case $cmp === 1:
                $high = $mid - 1;
                break;
            default:
                return -1;
                break;
        }
    }

    return -1;
}