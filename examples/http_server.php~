<?php
prggmr\load_module('http');

/**
 * Connect to the HTTP Server.
 */
$server = \prggmr\module\http\api\server('0.0.0.0:1337', function(){
    echo "Running at ".$this->get_address().PHP_EOL;
});

/**
 * On Connection
 */
$server->on_connect(function(){
    $request = $this->get_request();
    $this->write($request->post('username'));
    $this->write('HelloWorld');
});