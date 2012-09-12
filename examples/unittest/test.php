<?php
prggmr\load_module('unittest');
use prggmr\signal\unittest as unittest;

// Enable history
prggmr\save_event_history(true);

unittest\api\test(function(){
    $this->true(true);
    $this->true(true);
    $this->true(true);
    $this->true(false);
    $this->true(true);
    $this->true(true);
});

unittest\api\suite(function(){
    $this->setup(function(){
        $this->a = 1;
        echo "SETUP";
    });
    $this->teardown(function(){
        echo "TEARDOWN";
    });
    $this->test(function(){
        echo $this->a;
        $this->true(true);
    });
    $this->test(function(){
        echo $this->a;
        $this->false(true);
    });
});

prggmr\handle(function(){
    $tests = 0;
    foreach (prggmr\event_history() as $_node) {
        if ($_node[0] instanceof prggmr\signal\unittest\Event) {
            $tests++;
        }
    }
    echo PHP_EOL;
    echo "Ran $tests tests";
    echo PHP_EOL;
}, new prggmr\engine\signal\Loop_Shutdown());
