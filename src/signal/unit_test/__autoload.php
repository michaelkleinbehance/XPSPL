<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */
$dir = dirname(realpath(__FILE__));
require_once $dir.'/test.php';
require_once $dir.'/output.php';
require_once $dir.'/event.php';
require_once $dir.'/assertion.php';
require_once $dir.'/api.php';
require_once $dir.'/assertions/default.php';

use prggmr\signal\unit_test as t;

prggmr\handle(function(){
    t\Output::instance()->send("prggmr unit testing loaded!", t\Output::SYSTEM, true);
    t\Output::instance()->send("enjoy the greatness!", t\Output::SYSTEM, true);
}, prggmr\engine\Signals::LOOP_START);