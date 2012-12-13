<?php
namespace prggmr\module\socket\signal;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Base
 * 
 * Base socket signal.
 */
class Base extends \prggmr\Signal {
    /**
     * Socket Signals are unique
     * 
     * @var  boolean
     */
    protected $_unique = true;
}