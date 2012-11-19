Real-time signals, events and asynchronous non-blocking i/o for PHP.

## Run'n Gun Install

Run the following command and enter your pa$$w0rd!

    $ curl prggmr.org/prggmr | sh
    $ prggmr -v

This will download and install the latest stable release of prggmr.

## Set intervals and timeouts

    \prggmr\load_module('time');

    \prggmr\module\time\interval(10, function(){ 
        echo "10 milliseconds"; 
    });

## Signal and handle events

    <?php

    prggmr\handle('light.green', function(){
        echo "The light is green GO GO GO!"
    });

    prggmr\signal('light.green');

## Handle interruption

    <?php

    prggmr\handle('light.green', function(){
        echo "The ".$this->car." car is moving";
        $this->car_speed = 100;
    });

    prggmr\before('light.green', function(){
        $this->car = 'Honda S2000';
    });

    prggmr\after('light.green', function(){
        echo "The ".$this->car." is going ".$this->car_speed."MPH!!";
    });

    prggmr\signal('light.green');

## Asynchronous server

    <?php
    
    prggmr\load_signal('socket');

    $server = new prggmr\signal\socket\Server("0.0.0.0:1337");

    // On Connect
    $server->on_connect(function(){
        echo "New Connection".PHP_EOL;
        $this->write("Hello".PHP_EOL);
    });

    // On Disconnect
    $server->on_disconnect(function(){
        echo "Disconnecting".PHP_EOL;
        $this->write("Goodbye".PHP_EOL);
    });

    // Register the server
    prggmr\handle(function(){
        echo "Server is running at ".$this->get_address().PHP_EOL;
    }, $server);

## Mailing List

The prggmr mailing list is located here [mailing list](https://groups.google.com/forum/?fromgroups#!forum/prggmr).

## Versions

prggmr uses [semver](http://semver.org) you should too.

## Module Roadmap

The following Modules are on the development roadmap.


### Event Stream Server

W3C Event-Stream (Specs)[http://dev.w3.org/html5/eventsource/].

#### Example

```php
<?php

$socket = new \prggmr\signal\http\EventStream();

handle($socket->read(), function($bytes){
    // do something
});

handle($socket->write(), function($bytes){
    // do something
});
```

### prggmr Event Server

A real-time complex event event server.

### PEL - prggmr Event Language

An SQL language that can be used to communicate with the event server.

```sql
SIGNAL weeding WHEN groom AND bride AND church_bells IF groom.tuxedo = black
and bride.gown = white AND bride RECIEVED_AFTER groom
```
