<?php
namespace prggmr\module\socket\event;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Socket read event.
 */
class Read extends \prggmr\Event {
    
    use \prggmr\module\socket\Server;

    /**
     * The signal stream that opened this connection.
     *
     * @param  object
     */
    public $server = null;

    /**
     * Constructs a new read event.
     *
     * @param  resource  $socket  Socket that connected
     * @param  object  $server  Socket server object
     * @param  integer|null  $ttl  Time to live
     *
     * @return  void
     */
    public function __construct($socket, $server, $ttl = null)
    {
        $this->server = $server;
        $this->_socket = $socket;
        return parent::__construct($ttl);
    }
}