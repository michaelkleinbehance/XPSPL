<?php
namespace prggmr\module\http\server;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

define('HTTP_POST', 'POST');
define('HTTP_DELIM', "\r\n\r\n");

/**
 * Represents an HTTP Server Request.
 *
 * @depends  PECL HTTP
 */
class Request {

    /**
     * HTTP Post Vars
     */
    protected $_post = [];

    /**
     * HTTP Request Data
     */
    protected $_request = null;

    /**
     * Constructs a new HTTP Request
     *
     * @param  string  $headers  HTTP Request Headers
     */
    public function __construct($string)
    {
        $this->_request = http_parse_message($string);
        if ($this->_request->requestMethod === HTTP_POST) {
            $this->_parse_post_body();
        }
    }

    /**
     * Returns HTTP post variable.
     *
     * @param  string  $name  Name of the post variable
     *
     * @return  string
     */
    public function post($name = null)
    {
        if (null === $name) {
            return $this->_post;
        }
        if (isset($this->_post[$name])) {
            return $this->_post[$name];
        }
        return null;
    }

    /**
     * Parses the HTTP Post vars from a request.
     *
     * @return  array
     */
    protected function _parse_post_body(/* ... */)
    {
        if (!isset($this->_request->headers['Content-Type'])) {
            return;
        }

        $delim = http_parse_params($this->_request->headers['Content-Type']);

        if ($delim->params[0] == 'application/x-www-form-urlencoded') {
            parse_str($this->_request->body, $this->_post);
            return;
        }
        if ($delim->params[0] == 'multipart/form-data') {
            $boundary = '--'.$delim->params[1]["boundary"];
            $rawfields = explode($boundary, $this->_request->body);
            foreach ($rawfields as $_field) {
                $_content = explode(HTTP_DELIM, $_field, 2);
                // skip if not a whole block
                if (count($_content) <= 1) continue;
                // get the content
                $type = http_parse_params(trim($_content[0]));
                $data = trim($_content[1]);
                $this->_post[$type->params[2]['name']] = $data;
            }
        }
    }
}