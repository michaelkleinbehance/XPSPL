<?php
/**
 * Non-Blocking Server
 */
prggmr\load_module('socket');
prggmr\load_module('time');

$server = new prggmr\module\socket\server\Select("0.0.0.0:1337");

// On Connect
$server->on_connect(new prggmr\Handle(function(){
    $server = $this->get_server();
    if (!isset($this->server->count)) {
        $this->server->count = 0;
    } else {
        $this->server->count++;
        if ($this->server->count >= 100) {
            // If more than 500 after this
            prggmr\after($this->get_signal(), function() use ($server){
                $server->reconnect();
                $server->count = 0;
            });
        }
    }
    // $this->write("You sent me the following".PHP_EOL);
    // var_dump()
    $http_request = new HttpMessage($this->read());
    var_dump($http_request->getBody());
    // $headers = http_parse_message($read);
    // $cookies = http_parse_cookie($headers->headers['Cookie']);
    // $post = http_parse_post_params(http_parse_params($headers->body));
    // $content = [$headers, $post, $cookies];
    // $this->write(json_encode($content));
}, null));

// // On Disconnect
$server->on_disconnect(new prggmr\Handle(function(){
    // echo "Disconnecting".PHP_EOL;
}, null));

// Register the server
prggmr\handle($server, function(){
    echo "Server is running at ".$this->get_address().PHP_EOL;
});

/**
 * Parses an HTTP
 */

/**
 * Parses the HTTP Request POST Body into an array.
 * 
 * @param  object  $object  HTTP Request Body
 * 
 * @return   array
 */
function http_parse_post_params($object)
{
    if (!isset($object->params)) {
        return [];
    }
    $params = [];
    $param = false;
    $param_data = false;
    $has = false;
    while(current($object->params) !== false) {
        $_v = current($object->params);
        if ($_v == 'form-data') {
            $param = next($object->params)['name'];
        } elseif (false !== $param) {
            if ($_v == '') {
                if ($has) {
                    $params[$param] = $param_data;
                    $param = false;
                    $has = false;
                    $param_data = '';
                }
            } else {
                $has = true;
                $param_data .= $_v;
            }
        }
        next($object->params);
    }
    return $params;
}