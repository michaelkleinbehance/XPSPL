<?php
namespace prggmr\signal\time;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */
 
 /**
 * Timeout signal
 *
 * Trigger a signal based on a timeout.
 *
 * As of v2.0 timeouts can be set on a second, millisecond or microsecond basis
 */
class Timeout extends \prggmr\signal\Complex {

    /**
     * Variables to pass the timeout handle.
     * 
     * @var null|array
     */
    protected $_vars = null;

    /**
     * The time precision.
     *
     * @var  integer
     */
    protected $_precision = null;

    /**
     * Constructs a timeout signal.
     *
     * @param  int  $time  Microseconds before signaling.
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    public function __construct($time, $precision = \prggmr\Engine::IDLE_MILLISECONDS)
    {
        if (!is_int($time) || $time <= 0) {
            throw new \InvalidArgumentException(
                "Invalid or negative timeout given."
            );
        }
        $this->_precision = $precision;
        switch($this->_precision) {
            case \prggmr\Engine::IDLE_SECONDS:
                $this->_info = $time + time();
                break;
            case \prggmr\Engine::IDLE_MILLISECONDS:
                $this->_info = $time + milliseconds();
                break;
            case \prggmr\Engine::IDLE_MICROSECONDS:
                $this->_info = $time + microseconds();
                break;
        }
        parent::__construct();
    }
    
    /**
     * Determines the time in the future that this signal should trigger and
     * and sets the engines idle time until then. 
     * 
     * @return  void
     */
    public function routine($history = null)
    {
        $current = milliseconds();
        if (null === $this->_info) return false;
        if ($current > $this->_info) {
            $this->signal_this(true);
        } else {
            switch($this->_precision) {
                case \prggmr\Engine::IDLE_SECONDS:
                    $this->_routine->set_idle_seconds(
                        $this->_info - $current
                    );
                    break;
                case \prggmr\Engine::IDLE_MILLISECONDS:
                    $this->_routine->set_idle_milliseconds(
                        $this->_info - $current
                    );
                    break;
                case \prggmr\Engine::IDLE_MILLISECONDS:
                    $this->_routine->set_idle_microseconds(
                        $this->_info - $current
                    );
                    break;
            }
        }
        return true;
    }
}