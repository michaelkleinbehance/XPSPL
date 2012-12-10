<?php
namespace prggmr\module\socket;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

use \prggmr\engine\idle as idle;
use \prggmr\module\socket as socket;

/**
 * Socket stream server class that uses select.
 */
class Stream extends \prggmr\signal\Complex {

    use Server;

    /**
     * Connection signal.
     *
     * @var  object
     */
    protected $_connect = null;

    /**
     * Disconnect signal.
     *
     * @var  object
     */
    protected $_disconnect = null;

    /**
     * Read signal.
     *
     * @var  object
     */
    protected $_read = null;

    /**
     * Write signal.
     *
     * @var  object
     */
    protected $_write = null;

    /**
     * Instance of an engine to use for signaling.
     *
     * @var  null|object
     */
    protected $_engine = null;

    /**
     * Network address
     *
     * @var  string
     */
    protected $_address = null;

    /**
     * Type of connection.
     *
     * @var  string
     */
    protected $_type = null;

    /**
     * Clients Connected
     *
     * @var  array
     */
    protected $_clients = [];

    /**
     * Constructs a new network socket stream.
     *
     * @param  string  $address  Address to make the connection on.
     * @param  string  $type  The network connection type. tcp|udp
     *
     * @return  void
     */
    public function __construct($address, $type = 'tcp', $engine = null) 
    {
        $this->_address = $address;
        $this->_type = $type;
        if (null !== $engine && $engine instanceof \prggmr\Engine) {
            $this->_engine = $engine;
        } else {
            $this->_engine = \prggmr\prggmr();
        }
        // connect
        $this->_connect();

        // connect/disconnect/read/write
        $this->_connect = new socket\signal\Connect(sprintf('%s_connect',
            spl_object_hash($this)
        ));
        $this->_disconnect = new socket\signal\Disconnect(sprintf('%s_disconnect',
            spl_object_hash($this)
        ));
        $this->_read = new socket\signal\Read(sprintf('%s_read',
            spl_object_hash($this)
        ));
        $this->_write = new socket\signal\Write(sprintf('%s_write',
            spl_object_hash($this)
        ));

        $this->on_disconnect(new \prggmr\Handle(function(){
            fclose($this->get_socket());
        }, null, PHP_INT_MAX));

        parent::__construct();

        $this->_routine->add_signal(
            $this, new socket\event\Server($this->_socket)
        );
    }

    /**
     * Establishes the connection to the socket.
     *
     * @return  void
     */
    protected function _connect(/* ... */)
    {
        // Establish a connection
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        list($address, $port) = explode(':', $this->_address);
        $port = $port * 1;
        if (false === socket_bind($this->_socket, $address, $port)) {
            $code = socket_last_error();
            $str = socket_strerror($code);
            throw new \RuntimeException(sprintf(
                'Could not connect to socket (%s) - %s',
                $code, $str
            ));
        }
        // listen
        socket_listen($this->_socket);
        // force non-blocking
        socket_set_nonblock($this->_socket);
    }

    /**
     * Disconnects from the socket.
     *
     * @return  void
     */
    public function disconnect(/* ... */)
    {
        fclose($this->_socket);
    }
    
    /**
     * Reconnects the socket.
     *
     * It will attempt to close before reconnecting.
     *
     * @return  void
     */
    public function reconnect(/* ... */)
    {
        $this->disconnect();
        $this->_connect();
    }

    /**
     * Runs the server routine, this will register the idle function to
     * listen on the given socket.
     *
     * @return  boolean
     */
    public function routine($history = null) 
    {
        $this->_routine->set_idle(new idle\Func(function($engine){
            $idle = $engine->get_routine()->get_idles_available();
            // 30 second default wait
            $time = 30;
            if (count($this->_routine->get_signals()) !== 0) {
                $time = 0;
            } elseif (count($idle) == 2) {
                foreach ($idle as $_idle) {
                    if ($_idle instanceof idle\Time) {
                        $time = round($_idle->convert_length(
                            $_idle->get_time_left(), 
                            idle\Time::SECONDS
                        ), 3);
                        break;
                    }
                }
            }
            $read = array_merge([$this->_socket], $this->_clients);
            // $read = [$this->_socket];
            $write = $this->_clients;
            $ex = null;
            if (false !== $count = socket_select($read, $write, $ex, $time)) {
                if ($count == 0) return true;
                if (count($read) !== 0) {
                    foreach ($read as $_read) {
                        if (!in_array($_read, $this->_clients, true)) {
                            $socket = socket_accept($_read);
                            socket_set_nonblock($socket);
                            $this->_routine->add_signal(
                                $this->_connect,
                                new event\Connect($socket, $this)
                            );
                            $this->_clients[] = $socket;
                        } else {
                            $this->_routine->add_signal(
                                $this->_read,
                                new event\Read($_read, $this)
                            );
                        }
                    }
                }
                if (count($write) !== 0) {
                    foreach ($write as $_write) {
                        $this->_routine->add_signal(
                            $this->_write,
                            new event\Write($_write, $this)
                        );
                    }
                }
            }
        }));
        return true;
    }

    /**
     * Registers a new handle for new connections.
     *
     * @param  callable  $function  Function to call on connect.
     *
     * @return  object
     */
    public function on_connect($function)
    {
        if (!$function instanceof \prggmr\Handle) {
            $function = new \prggmr\Handle($function, null);
        }
        return $this->_engine->handle(
            $this->_connect, $function
        );
    }

    /**
     * Registers a new handle for client read.
     *
     * @param  callable  $function  Function to call on connect.
     *
     * @return  object
     */
    public function on_read($function)
    {
        if (!$function instanceof \prggmr\Handle) {
            $function = new \prggmr\Handle($function, null);
        }
        return $this->_engine->handle(
            $this->_read, $function
        );
    }

    /**
     * Registers a new handle for client write.
     *
     * @param  callable  $function  Function to call on connect.
     *
     * @return  object
     */
    public function on_write($function)
    {
        if (!$function instanceof \prggmr\Handle) {
            $function = new \prggmr\Handle($function, null);
        }
        return $this->_engine->handle(
            $this->_write, $function
        );
    }

    /**
     * Registers a new handle for disconnections.
     *
     * @param  callable  $function  Function to call on connect.
     *
     * @return  object
     */
    public function on_disconnect($function)
    {
        if (!$function instanceof \prggmr\Handle) {
            $function = new \prggmr\Handle($function, null);
        }
        return $this->_engine->handle(
            $this->_disconnect, $function
        );
    }

    /**
     * Sends the disconnection signal.
     *
     * @param  resource  $socket  Socket that disconnected
     *
     * @return  void
     */
    public function send_disconnect($socket)
    {
        $this->_routine->add_signal(
            $this->_disconnect,
            new socket\event\Disconnect($socket, $this)
        );
    }

    /**
     * Returns the prggmr engine used for this server.
     *
     * @return  object
     */
    public function get_engine(/* ... */)
    {
        return $this->_engine;
    }

    /**
     * Returns the address for the network socket.
     *
     * @return  string
     */
    public function get_address(/* ... */)
    {
        return $this->_address;
    }

    /**
     * Returns a new event for connection.
     *
     * @return  object
     */
    protected function _get_connection_event($socket)
    {
        return new socket\event\Connect($socket, $this);
    }

    /**
     * Returns the currently connected clients.
     *
     * @return  array
     */
    public function get_clients(/* ... */)
    {
        return $this->_clients;
    }
}