<?php
namespace prggmr\module\http\server\event;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

use \prggmr\module\http\server\Request;

/**
 * HTTP Server connection.
 */
class Connect extends \prggmr\module\socket\event\Connect {

    /**
     * The HTTP Request.
     */
    protected $_http = null;

    /**
     * Constructs a new HTTP connection event.
     *
     * @param  resource  $socket  Socket that connected
     * @param  object  $server  Socket server object
     * @param  integer|null  $ttl  Time to live
     *
     * @return  void
     */
    public function __construct($socket, $server, $ttl = null)
    {
        parent::__construct($socket, $server, $ttl);
        $this->_http = new Request($this->read());
    }

    /**
     * Gets the HTTP Request object.
     *
     * @return  object \prggmr\module\http\server\Request
     */
    public function get_request()
    {
        return $this->_http;
    }
}