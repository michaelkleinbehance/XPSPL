<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */
prggmr\load_module('unittest');

use prggmr\module\unittest as unittest;

// tmp class for state test
class Temp {
    use prggmr\State;
}

unittest\suite(function(){
    /**
     * Setup and teardown function
     */
    $this->setup(function(){
        $this->state = new Temp();
    });
    $this->teardown(function(){
        $this->state = null;
    });

    $this->test(function(){
        $this->equal($this->state->get_state(), STATE_DECLARED);
    }, 'Test default state');

});