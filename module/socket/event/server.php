<?php
namespace prggmr\module\socket\event;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Server connection event.
 */
class Server extends \prggmr\Event {

    use \prggmr\module\socket\Server;

    /**
     * Constructs a new socket event.
     *
     * @param  resource  $socket  Socket that connected
     * @param  integer|null  $ttl  Time to live
     *
     * @return  void
     */
    public function __construct($socket, $ttl = null)
    {
        $this->_socket = $socket;
        return parent::__construct($ttl);
    }

    /**
     * Calls functions within the signal.
     * 
     * @param  string  $method  Method name
     * @param  array  $vars  Variables
     */
    public function __call($method, $vars = [])
    {
        return call_user_func_array(array($this->get_signal(), $method), $vars);
    }
}