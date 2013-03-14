<?php
namespace network;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

import('logger');

use \XPSPL\idle\Process,
    \XPSPL\idle\Time;

/**
 * Socket
 *
 * Event driven I/O.
 */
class Socket extends \XPSPL\SIG_Routine {

    /**
     * Socket connection object
     *
     * @var  object
     */
    public $connection = null;

    /**
     * Socket Address
     *
     * @var  string
     */
    protected $_address = null;

    /**
     * Options used for the socket.
     */
    protected $_options = [];

    /**
     * Client sockets currently connected for read/write.
     *
     * @var  array
     */
    protected $_clients = [];

    /**
     * Constructs a new socket.
     *
     * @param  string  $address  Address to make the connection on.
     * @param  string  $options  Connection options
     *
     * @return  void
     */
    public function __construct($address, $options = []) 
    {
        parent::__construct();

        $defaults = [
            'port' => null,
            'domain' => AF_INET,
            'type' => SOCK_STREAM,
            'protocol' => SOL_TCP
        ];
        $options += $defaults;

        $this->_address = $address;
        $this->_options = $options;

        $this->_idle = new Process(function($processor){
            if (XPSPL_DEBUG) {
                logger(XPSPL_LOG)->debug(
                    'Entering socket wait loop'
                );
            }
            $idle = $processor->get_routine()->get_idles_available();
            // 30 second default wait
            $time = 30;
            $utime = 0;
            // Determine if another function has requested to execute in x
            // amount of time
            if (count($processor->get_routine()->get_signals()) !== 0) {
                // If we have signals to process only poll and continue
                $time = 0;
            } elseif (count($idle) >= 2) {
                foreach ($idle as $_idle) {
                    if ($_idle->get_idle() instanceof Time) {
                        $time = round($_idle->get_idle()->convert_length(
                            $_idle->get_idle()->get_time_left(), 
                            TIME_SECONDS
                        ), 3);
                        if ($time > 0 && $time < 1) {
                            $utime = $time * 1000;
                            $time = 0;
                        }
                        break;
                    }
                }
            }
            // establish sockets
            $re = [
                $this->connection->get_resource()
            ];
            $wr = $ex = [];
            foreach ($this->_clients as $_k => $_c) {
                $_resource = $_c->get_resource();
                // test if socket is still connected
                // send disconnect if disconnect detected
                if ($_c->is_connected() === false) {
                    emit(
                        new SIG_Disconnect($_c)
                    );
                    unset($this->_clients[$_k]);
                    continue;
                } else {
                    $re[] = $_resource;
                    $ex[] = $_resource;
                }
            }
            $count = socket_select($re, $wr, $ex, $time, $utime);
            if ($count === false) {
                logger(XPSPL_LOG)->debug(
                    'Socket wait loop ended PROBLEM'
                );
                return false;
            } elseif ($count == 0) {
                if (XPSPL_DEBUG) {
                    logger(XPSPL_LOG)->debug(
                        'Socket wait loop ended no connection changes'
                    );
                }
                return true;
            }
            if (XPSPL_DEBUG) {
                logger(XPSPL_LOG)->debug(
                    'Socket wait loop ended connection changes detected'
                );
            }
            // Check Read
            if (count($re) !== 0) {
                foreach ($re as $_r) {
                    if (XPSPL_DEBUG) {
                        logger(XPSPL_LOG)->debug(sprintf(
                            'Connection Read %s',
                            strval(intval($_r))
                        ));
                    }
                    $id = intval($_r);
                    if (!isset($this->_clients[$id])) {
                        $client = new Client($_r);
                        $id = intval($client->get_resource());
                        emit(
                            new SIG_Connect($this, $client)
                        );
                        $this->_clients[$id] = $client;
                        if (XPSPL_DEBUG) {
                            logger(XPSPL_LOG)->debug(sprintf(
                                'Added Connection %s',
                                strval(intval($id))
                            ));
                        }
                    } else {
                        emit(
                            new SIG_Read($this, $this->_clients[$id])
                        );
                    }
                }
            }
            // Check Write
            if (count($wr) !== 0) {
                foreach ($wr as $_write) {
                    if (XPSPL_DEBUG) {
                        logger(XPSPL_LOG)->debug(sprintf(
                            'Connection Write %s',
                            strval($_write)
                        ));
                    }
                    $processor->get_routine()->add_signal(
                        new SIG_Write(
                            $this, $this->_clients[intval($_write)]
                        )
                    );
                }
            }
        });
    }

    /**
     * Registers the idle process.
     *
     * @return  void
     */
    public function routine(\XPSPL\Routine $routine)
    {
        if (null === $this->connection) {
            $this->_connect();
            $routine->add_signal(new SIG_Connect($this->connection));
        }
        $routine->add_idle($this);
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
        $this->connection->disconnect();
        $this->connection = null;
        $this->_connect();
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
        return signal(new SIG_Disconnect($this), $function);
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
        return signal(new SIG_Read($this), $function);
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
        return signal(new SIG_Write($this), $function);
    }

    /**
     * Registers a new handle for new client connections.
     *
     * @param  callable  $function  Function to call on connect.
     *
     * @return  object
     */
    public function on_connect($function)
    {
        return signal(new SIG_Connect($this), $function);
    }

    /**
     * Returns the currently connected clients.
     *
     * @return  array
     */
    public function get_connections(/* ... */)
    {
        return $this->_clients;
    }

    /**
     * Establishes the socket connection.
     *
     * @return  void
     */
    protected function _connect(/* ... */)
    {
        if (null !== $this->connection) {
            return;
        }
        // Establish a connection
        $this->connection = new Connection(socket_create(
            $this->_options['domain'], 
            $this->_options['type'], 
            $this->_options['protocol']
        ));
        // timeout
        socket_set_option(
            $this->connection->get_resource(), 
            SOL_SOCKET, 
            SO_RCVTIMEO,
            [
                'sec' => XPSPL_NETWORK_TIMEOUT_SECONDS, 
                'usec' => XPSPL_NETWORK_TIMEOUT_MICROSECONDS
            ]
        );
        $bind = socket_bind(
            $this->connection->get_resource(), 
            $this->_address, 
            $this->_options['port']
        );
        if (false === $bind) {
            throw_socket_error();
        }
        // listen
        socket_listen($this->connection->get_resource());
        socket_set_nonblock($this->connection->get_resource());
    }
}