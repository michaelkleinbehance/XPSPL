<?php
prggmr\load_module('string');

prggmr\handle_loader('test_hey', dirname(realpath(__FILE__))."/loader");

prggmr\signal("test_hey");