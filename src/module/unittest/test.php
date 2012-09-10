<?php
namespace prggmr\module\unittest;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Unit testing signal
 * 
 * This allows for unit testing using signals.
 * 
 * Testing is performed as:
 * 
 * prggmr\module\unit_test\api\test(function(){
 *     $this->true(true);
 *     $this->false(false);
 *     $this->null(null);
 *     etc ...
 * });
 */
class Test extends \prggmr\signal\Complex {

    /**
     * Constructs a new test signal.
     * 
     * @param  string  $name  Name of the test.
     * @param  object  $event  prggmr\module\unittest\Event
     * 
     * @return  void
     */
    public function __construct($info = null, $event = null)
    {
        if (null !== $event && $event instanceof Event) {
            $this->_event = $event;
        }
        $this->_info = $info;
        parent::__construct($info);
    }

    /**
     * Routine evaluation.
     */
    public function routine($event_history = null)
    {
        if (null === $this->_event) {
            $this->_event = new Event();
        }
        $this->signal_this();
        // test signals always return to fire immediatly
        return true;
    }
}