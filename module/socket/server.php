<?php
namespace prggmr\module\socket;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Server class is used for creating network servers.
 */
trait Server {

    /**
     * The socket connection.
     *
     * @var  resource
     */
    protected $_socket = null;

    /**
     * Returns the socket connection.
     *
     * @return  resource
     */
    public function get_socket(/* ... */)
    {
        return $this->_socket;
    }

    /**
     * Sets the socket connection.
     *
     * @param  resource  $socket  Socket resource
     *
     * @return  void
     */
    public function set_socket($socket)
    {
        $this->_socket = $socket;
    }

    /**
     * Writes the given string to the socket.
     *
     * @param  string  $string  String to send.
     *
     * @return  integer
     */
    public function write($string)
    {
        echo "WRITE $string";
        return socket_write($this->_socket, $string);
    }

    /**
     * Reads the given length of data from a socket.
     *
     * @param  integer  $length  Maximum number of bytes to read in.
     *                           Default = 1MB
     * @param  integer  $flags  See http://php.net/manual/en/function.stream-socket-recvfrom.php
     *
     * @return  string
     */
    public function read($length = 1048576, $flags = 0) 
    {
        return socket_read($this->_socket, $length, $flags);
    }

    /**
     * Disconnects the socket connection.
     *
     * @param  integer  $how
     *
     * @return  boolean
     */
    public function disconnect($how = STREAM_SHUT_RDWR)
    {
        return fclose($this->_socket, $how);
    }

    /**
     * Returns the server signal.
     *
     * @return  object
     */
    public function get_server(/* ... */)
    {
        return $this->server;
    }
}