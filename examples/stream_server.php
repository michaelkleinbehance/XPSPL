<?php

prggmr\load_module('socket');

$server = new \prggmr\module\socket\Stream('0.0.0.0:1337');

$GLOBALS['clients'] = [];

$server->on_read(function(){
    $server = $this->get_server();
    $in = $this->read();
    $user = array_search($this->get_socket(), $GLOBALS['clients']);
    if (is_int($user)) {
        unset($GLOBALS['clients'][$user]);
        $GLOBALS['clients'][$in] = $this->get_socket();
        foreach ($this->get_server()->get_clients() as $_client) {
            if ($this->get_socket() != $_client) {
                socket_write($_client, $in.' Connected');
            }
        }
        $user = $in;
        return true;
    }
    foreach ($server->get_clients() as $_client) {
        if ($this->get_socket() != $_client) {
            socket_write($_client, $user . ' : ' . $in);
        }
    }
});

$server->on_connect(function(){
    socket_write($this->get_socket(),
'
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
##########  ##########  ##########  ##########  ####    ####  ########## 
##      ##  ##      ##  ##          ##          ## ##  ## ##  ##      ##
##########  ##########  ##    ####  ##    ####  ##   ##   ##  ##########
##          ##     ##   ##########  ##########  ##        ##  ##     ##
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
'
);
    $GLOBALS['clients'][] = $this->get_socket();
    socket_write($this->get_socket(), 'Enter your username : ');
});

$server->on_disconnect(function(){
    foreach ($this->get_server()->get_clients() as $_client) {
        if ($this->get_socket() != $_client) {
            socket_write($_client, 'Client Disconnected');
        }
    } 
});

\prggmr\handle($server, function(){
    echo "Chat server running on ".$this->get_address();
});