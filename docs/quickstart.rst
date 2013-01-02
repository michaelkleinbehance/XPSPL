Quickstart
----------

*Updated - Dec 28, 2012*

This guide provides an introduction into programming with XPSPL.

It is recommended that you have at a minimum 2 hours of time to cover this guide 
at a glance and a full day to cover it top to bottom.

This guide covers the following topics,

.. contents::

Signal Driven Programming with XPSPL
====================================

Signal driven programming is the development of software using a design where 
the flow is determined by signals.

It relies heavily on the use of event processes, interruptions, mutable data and 
insane levels of decoupling.

If you know GUI this will feel very familiar.

The idea is nothing new and is in use right now on the device your reading this 
with, unless your on paper.

Designing software using SDP is not much different than designing it using 
OOP or functional type designs only in that it provides programmers more power 
in directing and interrupting flow.

SDP is not a replacement for your current software design.

In many situations SDP is not the choice for performing a process.

SDP is only a good candidate under the following circumstances,

   * The software must be a long served process that can handle tens to thousands 
     of separate operations occurring at any given time.

   * The software has the probability that it will require enhancements to it's 
     core flow causing potential rewrites of production stable versions.

   * Many concurrent unrelated processes must be performed using the same data.

It should be mentioned that SDP suites well for short-lived software as 
seen with most standard PHP web applications.

Examples
________

These examples are not real world and are for informational purposes only.

Echo Server
%%%%%%%%%%%

This example is a network server that echos the client back it's own data it 
sent.

.. code-block:: php

    <?php
    /**
    * Echo Server
    *
    * This example demonstrates a simple echo server that spits back anything that
    * was sent and then disconnects.
    */
   import('network');
   
   $socket = network\connect('0.0.0.0', ['port' => '1337'], function(){
       echo "Server Running on " . $this->socket->get_address() . PHP_EOL;
   });
   
   $socket->on_client(null_exhaust(function(){
       echo "Connection " . PHP_EOL;
       $this->socket->write($this->socket->read());
       $this->socket->disconnect();
   }));

Flow Interruptions
%%%%%%%%%%%%%%%%%%

This example demonstrates interruption the flow of a signal.

.. code-block:: php

    <?php
    // When foo is emitted insert bar into the event
    before(new Foo(), function($event){
        $event->bar = 'foo';
    });

    // Handle Foo
    signal(new Foo(), function($event){
        echo $event->bar;
    });

    // After foo is emitted unset bar in the event
    after(new Foo(), function($event){
        unset($event->bar);
    });

    emit(new Foo());

Network Switch Server
%%%%%%%%%%%%%%%%%%%%%

Let's examine a more real world example.

Take the following network switch server that transmits start and stop signals 
from an outside device to an HTML document in a video recording device.

.. code-block:: php

    <?php
    /**
     * Load the networking and time modules.
     */
    load_module('network');
    load_module('time');

    // Create a new network connection
    $connection = network\connect('0.0.0.0:1337');

    // Failsafe awake signal
    $awake = new time\SIG_Awake(45, TIME_SECONDS);

    // When a connection is received perform the following
    // * Check the client device type
    // * If request device check if video connected and emit requested signal 
    // * If video device set as video device in server
    $connection->on_client(function($client, $server){
        // Read in the giving data from connected client
        $client = json_parse($client->data);
        // Check the client type
        // For devices that communicate in
        if ($client->type === DEVICE_REQUEST) {
            // Check and error back to device if no video device
            if ($server->device_video) {
                $client->write("{error: 'Video device not connected';}");
                $client->disconnect();
            }
            // Check command from device
            if ($data->start) {
                emit(new SIG_Video_Device_Start(), $server->device_video);
                // Failsafe to shutdown the device 45 seconds after connecting
                if (is_exhausted($awake)) {
                    time\awake(45, function() use ($server){
                        signal(
                            new SIG_Video_Device_Stop(), 
                            $server->device_video
                        );
                    }, TIME_SECONDS);
                }
            }
            if ($data->stop) {
                emit(new SIG_Video_Device_Stop(), $server->device_video);
            }
            $client->disconnect();
            return;
        }
        // Video device we send signals
        if ($client.type === DEVICE_VIDEO) {
            $server->device_video = $client;
        }
        return;
    });

    /**
     * Handles the video device start signal
     */
    signal(new SIG_Video_Device_Start(), non_exhaust(function($device){
        $device->write(write_video_cmd(false, true));
    }));

    /**
     * Handles the video device stop signal
     */
    signal(new SIG_Video_Device_Stop(), non_exhaust(function($device){
        $device->write(write_video_cmd(false, true));
    });

    /**
     * Prepares a JSON message to send the video device
     */
    function write_video_cmd($start = false, $stop = false) 
    {
        $obj = new stdClass();
        $obj->start = $start;
        $obj->stop = $stop;
        return json_encode($obj);
    }

XPSPL The PHP Signal Library
============================

History
_______

Code for XPSPL began sometime in 2008 as a project to learn EDP, though the name 
and design have changed a few times since then, the goal of changing the way we 
write software has not.

On Nov 10, 2010 an early version was uploaded to the open-source community.

By late 2011 XPSPL began use in production stable software and continues to this 
day.

Limitations
___________

I always find it is best to know what something can't do before what it can.

Here is a list of unsupported features,

    * Threads and forks
    * epoll, kqueue, poll (select is supported)
    * Guaranteed real time

A suitable epoll, kqueue and poll module is planned but requires funding.

Contributions for these features are always appreciated.

Performance
-----------

XPSPL performance well when using less than 10,000 signals, once pushed over performance can become erratic.

The following performance test was generated on a 2.7GHZ i5 processor.

.. raw:: html

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var sig_inst = [['Time', 'Signals Installed']];
        var sig_emit = [['Time', 'Signals Emitted']];
                sig_emit.push([1.8119812011719E-5, 2]);
        sig_emit.push([3.2186508178711E-5, 4]);
        sig_emit.push([6.6995620727539E-5, 8]);
        sig_emit.push([0.013117074966431, 16]);
        sig_emit.push([0.00025606155395508, 32]);
        sig_emit.push([0.00048303604125977, 64]);
        sig_emit.push([0.00095605850219727, 128]);
        sig_emit.push([0.0020380020141602, 256]);
        sig_emit.push([0.00376296043396, 512]);
        sig_emit.push([0.0071640014648438, 1024]);
        sig_emit.push([0.015555858612061, 2048]);
        sig_emit.push([0.083690166473389, 4096]);
        sig_emit.push([0.063318014144897, 8192]);
        sig_emit.push([0.2115318775177, 16384]);
        sig_inst.push([5.1021575927734E-5, 2]);
        sig_inst.push([4.5061111450195E-5, 4]);
        sig_inst.push([7.6055526733398E-5, 8]);
        sig_inst.push([0.00017189979553223, 16]);
        sig_inst.push([0.00034117698669434, 32]);
        sig_inst.push([0.00063896179199219, 64]);
        sig_inst.push([0.0012118816375732, 128]);
        sig_inst.push([0.0023870468139648, 256]);
        sig_inst.push([0.0045371055603027, 512]);
        sig_inst.push([0.0094959735870361, 1024]);
        sig_inst.push([0.019057035446167, 2048]);
        sig_inst.push([0.050827026367188, 4096]);
        sig_inst.push([0.12461400032043, 8192]);
        sig_inst.push([0.2510769367218, 16384]);
        var data_1 = google.visualization.arrayToDataTable(sig_emit);
        var data_2 = google.visualization.arrayToDataTable(sig_inst);
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data_1, {title: 'Signal Emit'});
        var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
        chart.draw(data_2, {title: 'Signal Install'});
      }
    </script>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
    <div id="chart_div2" style="width: 900px; height: 500px;"></div>

API
____

XPSPL's API is designed to provide programmers with a natural speaking, 
intuitive API.

The API has been extensively redesigned based on instinctual memory and usage 
feedback from a team of highly skilled programmers.

Non-Modular API functions are not namespaced and should not provide any collisions 
with your existing system*.

.. note::

    *Due to unknown system configurations it cannot be guaranteed that collisions
    wont exist.

Samples
_______

OOP
___

.. code-block:: php

   <?php

   /**
    * This is a standard class used for math operations.
    */
   class Math {

      /**
       * This method will adds two numbers giving.
       */
      public function add($num_1, $num_2) 
      {
         return $num_1 + $num_2;
      }

   }

   /**
    * Add two numbers using our class.
    */
   $math = new Math();
   echo $math->add(1, 4);

   // Results
   5

Using XPSPL.

.. code-block:: php

    <?php

    /**
    * This is standard listener used for math operations.
    */
    class Math {

      /**
       * Receive the add signal.
       */
      public function add($event)
      {
        return $event->num_1 + $this->num_2;
      }
    }

    listen(new Math());
    $event = new Event();
    $event->num_1 = 1;
    $event->num_2 = 4;
    signal('add', $event);

    // Results
    echo $event->result;

Functional
__________

.. code-block:: php

    <?php

    /**
    * This is a standard function for adding to numbers.
    */
    function add($num_1, $num_2) 
    {
        return $num_1 + $num_2;
    }

    echo add(1, 4);

    // Results
    5

Using XPSPL.

.. code-block:: php

    <?php

    /**
    * This is a standard process for adding to numbers.
    */
    function add($process)
    {
        return $process->num_1 + $process->num_2'
    }

    handle('add', add);

    $event = new Event();
    $event->num_1 = 1;
    $event->num_2 = 4;
    signal('add', $event);
    echo $event->result;

    // Results
    5

Closures
________

.. code-block:: php

    <?php

    $add = function($num_1, $num_2) {
        return $num_1 + $num_2;
    }

    echo $add(1, 4);

    // Results
    5

Using XPSPL

.. code-block:: php

    <?php

    handle('add', function(){
        return $this->num_1 + $this->num_2;
    });

    $event = new Event();
    $event->num_1 = 1;
    $event->num_2 = 4;
    signal('add', $event);
    echo $event->result;

    // Results
    5

Environment
===========

XPSPL is designed to run applications from inside an event loop.

It ships with the ``xpepl`` command for loading applications into its environments.

Developers writing an application that will be a long served process will typically want to run their applications 
using this command.

XPSPL understands the following commands.

=============  ===============
Command        Performs Action
=============  ===============
-c,--config    Loads the giving file for XPSPL's runtime configuration
-h,--help      Displays the XPSPL help message
-p,--passthru  Ignore any subsequent arguments and pass them to the loaded file.
--test         Run XPSPL's unittests
--test-cover   Run XPSPL's unittests and include code coverage information (Requires xdebug)
-t/--time      Inform the loop to run for the given amount of milliseconds before shutting down.
-v/--version   Prints the current version of XPSPL.
=============  ===============

Starting applications
____________________

Applications must be started from a single file loaded with XPSPL.

.. code-block:: console

   $ XPSPL main.php

Managing applications
_____________________

Currently XPSPL does not support managing itself as a daemon.

We currently use runit for managing long lived processes, though any process manager you are familiar with will work just as well.

Short lived applications
_______________________

For applications that will have a very short life cycle, such as those typically loaded from an external interface (an HTTP Request) 
you will need to manually load and enter your application into the event loop.

To do so you can use the following code as your ``index.php``.

.. code-block:: php

   <?php
   // Define any configuration options here
   // ...
   // ...
   // ...
   
   // load the XPSPL library
   require_once 'XPSPL/src/XPSPL.php';

   // This would be your main file.
   require_once 'your_main_file.php';
   
   // Start the event loop
   XPSPL\loop();

.. note::

   Notice the last line calls ``XPSPL\loop``? 

   This must be the last line of code executed in your application since this will block anything that follows.


Signals, Handles and Events
===========================

.. Signals
.. _______

.. A signal is the introduction of change within an application.

.. They are represented as classes or strings using two seperate types.

.. Standard
.. ********

.. Standard signals are signals which do not require a computation to trigger, can be represented in string form, are triggered via the ``XPSPL\signal`` function and extend the ``XPSPL\Signal`` class.

.. Examples
.. %%%%%%%%

.. .. code-block:: php

..    <?php
..    // Register a new simple signal as a string
..    XPSPL\register('foo');
   
..    // Register a new simple signal as a class
..    class Bar extends XPSPL\Signal {}
..    XPSPL\register(new Bar());

.. Complex
.. *******

.. Complex signals are signals which do require a computation to trigger, cannot be represented in string form, cannot be triggered via the ``XPSPL\signal`` function and extend the ``XPSPL\signal\Complex`` class.

.. The computations required to trigger fall into two seperate types of categories, an evaluation and routine.

.. Evaluations
.. %%%%%%%%%%%

.. A complex signal evaluation is the process in which a signal will analyze the currently processing signal to determine its execution possibilities.

.. Routines
.. %%%%%%%%

.. A routine is a signal which runs with each loop iteration for analyzing the past and present events that have taken place to determine its execution possibilities for now and in the future.

   
