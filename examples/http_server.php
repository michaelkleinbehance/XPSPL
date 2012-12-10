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
    var_dump($this);
    $request = $this->get_request();
    switch ($request->getRequestUrl()) {
        case '/start':
            $this->write("START THE CAM");
            break;
        case '/stop':
            $this->write("STOP THE CAM");
            break;
        default:
            $this->write("UNKNOWN");
            break;
    }
});
 