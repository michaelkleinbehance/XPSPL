<?php
namespace prggmr\module\cron;

/**
 * Setup a cron based signal.
 *
 * @param  string  $expression  Cron expression
 * @param  callable  $callable  Callable variable.
 *
 * @return  array  [signal, handle]
 */
function cron($expression, $callable) {
    $signal = new Signal($expression);
    if (!$callable instanceof \prggmr\Handle) {
        $callable = new \prggmr\Handle($callable, null);
    }
    $handle = \prggmr\handle($signal, $callable);
    return [$signal, $handle];
}