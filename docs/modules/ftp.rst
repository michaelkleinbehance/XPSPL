.. ftp::

FTP Module
----------

The FTP Module provides Non-Blocking FTP transfers for XPSPL.

.. note::

    Currently only uploading files to a remote server is supported.

Installation
____________

The FTP Module is bundled with XPSPL as of version 3.0.0.

Requirements
%%%%%%%%%%%%

PHP
^^^

PHP FTP_ extension must be installed and enabled. 

.. _FTP: http://php.net/manual/en/book.ftp.php

XPSPL
^^^^^

XPSPL **>= 3.0**

Configuration
_____________

The FTP Module has no runtime configuration options available.

Usage
_____

Importing
%%%%%%%%%

.. code-block:: php
    
    <?php

    import('ftp');

Uploading Files
%%%%%%%%%%%%%%%

.. code-block:: php
    
    <?php

    import('ftp');

    $files = ['/tmp/myfile_1.txt', '/tmp/myfile_2.txt'];
    $server = [
        'hostname' => 'ftp.myhost.com',
        'username' => 'foo',
        'password' => 'bar'
    ];

    $upload = ftp\upload($files, $server);

    ftp\complete($upload, null_exhaust(function(){
        $file = $this->get_file();
        echo sprintf('%s has uploaded'.PHP_EOL,
            $file->get_name() 
        );
    }));

    ftp\failure($upload, null_exhaust(function(){
        $file = $this->get_file();
        echo sprintf('%s has failed to upload'.PHP_EOL,
            $file->get_name() 
        );
    }));

API
___

All functions and classes are under the ``ftp`` namespace.

.. function:: ftp\\upload(array $files, array $connection, [callable $callback = null])

   Performs a non-blocking FTP upload of the given file(s).

   When multiple files are given they will be uploaded simultaneously using 
   separate connections to the given ``$connection``.

   The ``$callback`` will be called once the files begin uploading.

   It is expected that the absolute path to the file will be given, failure to 
   do so will cause unexpected behavior.

   The connection array accepts,

   * **hostname** - Hostname of the server to upload.
   * **username** - Username to use when connecting.
   * **password** - Password to use when connecting.
   * **port** - Port number to connect on. *Default=21*
   * **timeout** - Connection timeout in seconds. *Default=90*

   :param array $files: Files to upload
   :param array $connection: FTP Connection options
   :param callable $callback: Function to call when upload begins
   :return object: SIG_Upload


TEST
----
.. src/api.php generated using docpx on 01/07/13 08:38pm
Functions
---------
.. function::  signal
   
  Installs a new signal processor.

  :param string|integer|object $signal: Signal to attach the process.
  :param object $callable: Callable
  :return object|boolean: Process, boolean if error


.. function::  null_exhaust
   
  Creates a never exhausting signal processr.

  :param callable|process $process: PHP Callable or \XPSPL\Process object.
  :return object: Process


.. function::  high_priority
   
  Creates or sets a process with high priority.

  :param callable|process $process: PHP Callable or \XPSPL\Process object.
  :return object: Process


.. function::  low_priority
   
  Creates or sets a process with low priority.

  :param callable|process $process: PHP Callable or \XPSPL\Process object.
  :return object: Process


.. function::  priority
   
  Sets a process priority.

  :param callable|process $process: PHP Callable or \XPSPL\Process object.
  :param integer $priority: Priority
  :return object: Process


.. function::  remove_process
   
  Removes an installed signal process.

  :param string|integer|object $signal: Signal process is attached to.
  :param object $process: Process instance.
  :return void: 


.. function::  emit
   
  Signals an event.

  :param string|integer|object $signal: Signal or a signal instance.
  :param array $vars: Array of variables to pass the processs.
  :param object $event: Event
  :return object: \XPSPL\Event


.. function::  signal_history
   
  Returns the signal history.

  :return array: 


.. function::  register_signal
   
  Registers a signal in the processor.

  :param string|integer|object $signal: Signal
  :return object: Queue


.. function::  search_signals
   
  Searches for a signal in storage returning its storage node if found,
optionally the index can be returned.

  :param string|int|object $signal: Signal to search for.
  :param boolean $index: Return the index of the signal.
  :return null|array: [signal, queue]


.. function::  loop
   
  Starts the XPSPL loop.

  :return void: 


.. function::  shutdown
   
  Sends the loop the shutdown signal.

  :return void: 


.. function::  import
   
  Import a module.

  :param string $name: Module name.
  :param string|null $dir: Location of the module.
  :return void: 


.. function::  before
   
  Registers a function to interrupt the signal stack before a signal fires,
allowing for manipulation of the event before it is passed to processs.

  :param string|object $signal: Signal instance or class name
  :param object $process: Process to execute
  :return boolean: True|False false is failure


.. function::  after
   
  Registers a function to interrupt the signal stack after a signal fires.
allowing for manipulation of the event after it is passed to processs.

  :param string|object $signal: Signal instance or class name
  :param object $process: Process to execute
  :return boolean: True|False false is failure


.. function::  XPSPL
   
  Returns the XPSPL processor.

  :return object: XPSPL\Processor


.. function::  clean
   
  Cleans any exhausted signal queues from the processor.

  :param boolean $history: Erase any history of the signals cleaned.
  :return void: 


.. function::  delete_signal
   
  Delete a signal from the processor.

  :param string|object|int $signal: Signal to delete.
  :param boolean $history: Erase any history of the signal.
  :return boolean: 


.. function::  erase_signal_history
   
  Erases any history of a signal.

  :param string|object $signal: Signal to be erased from history.
  :return void: 


.. function::  disable_signaled_exceptions
   
  Disables the exception processr.

  :param boolean $history: Erase any history of exceptions signaled.
  :return void: 


.. function::  erase_history
   
  Cleans out the entire event history.

  :return void: 


.. function::  save_signal_history
   
  Sets the flag for storing the event history.

  :param boolean $flag: 
  :return void: 


.. function::  listen
   
  Registers a new event listener object in the processor.

  :param object $listener: The event listening object
  :return void: 


.. function::  dir_include
   
  Performs a inclusion of the entire directory content, including 
subdirectories, with the option to start a listener once the file has been 
included.

  :param string $dir: Directory to include.
  :param boolean $listen: Start listeners.
  :param string $path: Path to ignore when starting listeners.
  :return void: 


.. function::  $i
   
  This is some pretty narly code but so far the fastest I have been able 
to get this to run.



.. function::  current_signal
   
  Returns the current signal in execution.

  :param integer $offset: In memory hierarchy offset +/-.
  :return object: 


.. function::  current_event
   
  Returns the current event in execution.

  :param integer $offset: In memory hierarchy offset +/-.
  :return object: 


.. function::  on_shutdown
   
  Call the provided function on processor shutdown.

  :param callable|object $function: Function or process object
  :return object: \XPSPL\Process


.. function::  on_start
   
  Call the provided function on processor start.

  :param callable|object $function: Function or process object
  :return object: \XPSPL\Process


.. function::  XPSPL_flush
   
  Empties the storage, history and clears the current state.

  :return void: 


