<?php
namespace prggmr\module\http;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

use \prggmr\module\socket\server\Select;

if (!defined('HTTP_SERVER_LIMIT')) {
    define('HTTP_SERVER_LIMIT', 100);
}

/**
 * Server
 *
 * Represents an HTTP Server.
 *
 * Currently the server uses UNIX select sockets.
 */
class Server extends Select {

    /**
     * Total connections handled
     *
     * @var  integer
     */
    public $handled = 0;

    /**
     * Constructs a new HTTP server.
     *
     * @param  string  $address  Network Address
     *
     * @return  void
     */
    public function __construct($address)
    {
        parent::__construct($address, 'tcp');
        $this->on_connect(new \prggmr\Handle(function(){
            $server = $this->get_server();
            if ($server->handled >= HTTP_SERVER_LIMIT) {
                $server->reconnect();
                $server->handled = 0;
            }
            $server->handled++;
        }, null, 0));
    }

    /**
     * Returns a new event for connection.
     *
     * @return  object
     */
    protected function _get_connection_event($socket)
    {
        return new server\event\Connect($socket, $this);
    }
}