<?php
namespace prggmr\engine;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The routine class is used by the engine during the routine calculation for
 * storing the idle functions and the signals which should be triggered in the
 * loop.
 *
 * This was added due to the current loop not providing a simple means for
 * objects inside the loop determining what has happened within the routine
 * calculation and the functionality required for the upgraded idle required
 * more complex algorithms which would not fit well inside the entire routine
 * loop method.
 */
final class Routine {

    /**
     * Signals that are ready to trigger in the loop.
     *
     * @var  array
     */
    protected $_signals = [];

    /**
     * Idle objects
     *
     * @var  array
     */
    protected $_idle = [];

    /**
     * Returns the signals to trigger in the loop.
     *
     * @return  array
     */
    public function get_signals(/* ... */)
    {
        return $this->_signals;
    }

    /**
     * Returns the object to idle the engine.
     *
     * This will only return a single object which has the greatest priority.
     *
     * @return  integer
     */
    public function get_idle(/* ... */)
    {
        return (isset($this->_idle[0])) ? $this->_idle[0] : false;
    }

    /**
     * Returns the objects createed to idle the engine.
     *
     * @return  integer
     */
    public function get_idles_available(/* ... */)
    {
        return $this->_idle;
    }

    /**
     * Adds a new function to idle the engine.
     *
     * @param  object  $idle  Idle
     *
     * @return  void
     */
    public function add_idle($idle)
    {
        if (!$idle instanceof Idle) {
            throw new \InvalidArgumentException(
                "Idle must be an instance of prggmr\engine\Idle"
            );
        }
        foreach ($this->_idle as $_k => $_func) {
            if (get_class($_func) === get_class($idle)) {
                if (!$_func->allow_override()) {
                    throw new \RuntimeException(sprintf(
                        "Idle class %s does not allow override",
                        get_class($_func)
                    ));
                }
                if ($_func->override($idle)) {
                    $this->_idle[$_k] = $idle;
                }
                return;
            }
        }
        $this->_idle[] = $idle;
        if (count($this->_idle) >= 2) {
            usort($this->_idle, function($a, $b){
                $a = $a->get_priority();
                $b = $b->get_priority();
                if (null === $a) {
                    return -1;
                }
                if (null === $b) {
                    return 1;
                }
                if ($a == $b) {
                    return 0;
                }

                return ($a < $b) ? -1 : 1;
            });
        }
    }
    
    /**
     * Adds a signal to trigger in the loop.
     *
     * @return  array
     */
    public function add_signal($signal, $vars = null, $event = null)
    {
        return $this->_signals[] = [$signal, $vars, $event];
    }
}