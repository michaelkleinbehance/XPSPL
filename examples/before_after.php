<?php

prggmr\before('test', function(){
    echo "BEFORE THIS".PHP_EOL;
});

prggmr\after("test", function(){
    echo "AFTER THIS".PHP_EOL;
}, prggmr\Engine::INTERRUPT_POST);

prggmr\handle("test", function(){
    echo "WTF".PHP_EOL;
});

prggmr\signal("test");