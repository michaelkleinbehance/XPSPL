<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */
namespace time;

/**
 * Wakes the system loop and runs the provided function.
 *
 * @param  integer  $time  Time to wake in.
 * @param  callable  $callback  Callable function.
 * @param  integer  $instruction  The time instruction. Default = Milliseconds
 *
 * @return  array  [signal, handle]
 */
function awake($time, $callback, $instruction = TIME_SECONDS)
{
    $signal = new SIG_Awake($time, $instruction);
    return [$signal, signal($signal, $callback)];
}

/**
 * Wakes the system using CRON expressions.
 *
 * @param  string  $cron  CRON Expression
 * @param  callable  $callback  Callback function to run
 *
 * @return  array [signal, handle]
 */
function CRON($cron, $callback)
{
    $signal = new SIG_CRON($cron);
    return [$signal, signal($signal, $callback)];
}