API
***

XPSPL API

All functions declared on this page do not fall into any namespace.

.. api.php generated using docpx on 01/08/13 03:51pm
.. function:: signal($signal, $callable)


    Installs a new signal processor.

    :param string|integer|object $signal: Signal to attach the process.
    :param object $callable: Callable

    :rtype: object|boolean Process, boolean if error


Example
-------

.. code-block:: php
    signal(new SIG_Startup(), function(){
        echo 'Doing something on startup';
    });



.. function:: null_exhaust($process)


    Creates a never exhausting signal process.

    :param callable|process $process: PHP Callable or \XPSPL\Process object.

    :rtype: object Process



.. function:: high_priority($process)


    Creates or sets a process with high priority.

    :param callable|process $process: PHP Callable or \XPSPL\Process object.

    :rtype: object Process



.. function:: low_priority($process)


    Creates or sets a process with low priority.

    :param callable|process $process: PHP Callable or \XPSPL\Process object.

    :rtype: object Process



.. function:: priority($process, $priority)


    Sets a process priority.

    :param callable|process $process: PHP Callable or \XPSPL\Process object.
    :param integer $priority: Priority

    :rtype: object Process



.. function:: remove_process($signal, $process)


    Removes an installed signal process.

    :param string|integer|object $signal: Signal process is attached to.
    :param object $process: Process instance.

    :rtype: void 



.. function:: emit($signal, [$event = false])


    Signals an event.

    :param string|integer|object $signal: Signal or a signal instance.
    :param array $vars: Array of variables to pass the processs.
    :param object $event: Event

    :rtype: object \XPSPL\Event



.. function:: signal_history()


    Returns the signal history.

    :rtype: array 



.. function:: register_signal($signal)


    Registers a signal in the processor.

    :param string|integer|object $signal: Signal

    :rtype: object Queue



.. function:: search_signals($signal, [$index = false])


    Searches for a signal in storage returning its storage node if found,
    optionally the index can be returned.

    :param string|int|object $signal: Signal to search for.
    :param boolean $index: Return the index of the signal.

    :rtype: null|array [signal, queue]



.. function:: loop()


    Starts the XPSPL loop.

    :rtype: void 



.. function:: shutdown()


    Sends the loop the shutdown signal.

    :rtype: void 



.. function:: import($name, [$dir = false])


    Import a module.

    :param string $name: Module name.
    :param string|null $dir: Location of the module.

    :rtype: void 



.. function:: before($signal, $process)


    Registers a function to interrupt the signal stack before a signal fires,
    allowing for manipulation of the event before it is passed to processs.

    :param string|object $signal: Signal instance or class name
    :param object $process: Process to execute

    :rtype: boolean True|False false is failure



.. function:: after($signal, $process)


    Registers a function to interrupt the signal stack after a signal fires.
    allowing for manipulation of the event after it is passed to processs.

    :param string|object $signal: Signal instance or class name
    :param object $process: Process to execute

    :rtype: boolean True|False false is failure



.. function:: XPSPL()


    Returns the XPSPL processor.

    :rtype: object XPSPL\Processor



.. function:: clean([$history = false])


    Cleans any exhausted signal queues from the processor.

    :param boolean $history: Erase any history of the signals cleaned.

    :rtype: void 



.. function:: delete_signal($signal, [$history = false])


    Delete a signal from the processor.

    :param string|object|int $signal: Signal to delete.
    :param boolean $history: Erase any history of the signal.

    :rtype: boolean 



.. function:: erase_signal_history($signal)


    Erases any history of a signal.

    :param string|object $signal: Signal to be erased from history.

    :rtype: void 



.. function:: disable_signaled_exceptions([$history = false])


    Disables the exception process.

    :param boolean $history: Erase any history of exceptions signaled.

    :rtype: void 



.. function:: erase_history()


    Cleans out the entire event history.

    :rtype: void 



.. function:: save_signal_history($flag)


    Sets the flag for storing the event history.

    :param boolean $flag: 

    :rtype: void 



.. function:: listen($listener)


    Registers a new event listener object in the processor.

    :param object $listener: The event listening object

    :rtype: void 



.. function:: dir_include($dir, [$listen = false, [$path = false]])


    Performs a inclusion of the entire directory content, including 
    subdirectories, with the option to start a listener once the file has been 
    included.

    :param string $dir: Directory to include.
    :param boolean $listen: Start listeners.
    :param string $path: Path to ignore when starting listeners.

    :rtype: void 



.. function:: $i()


    This is some pretty narly code but so far the fastest I have been able 
    to get this to run.



.. function:: current_signal([$offset = false])


    Returns the current signal in execution.

    :param integer $offset: In memory hierarchy offset +/-.

    :rtype: object 



.. function:: current_event([$offset = false])


    Returns the current event in execution.

    :param integer $offset: In memory hierarchy offset +/-.

    :rtype: object 



.. function:: on_shutdown($function)


    Call the provided function on processor shutdown.

    :param callable|object $function: Function or process object

    :rtype: object \XPSPL\Process



.. function:: on_start($function)


    Call the provided function on processor start.

    :param callable|object $function: Function or process object

    :rtype: object \XPSPL\Process



.. function:: XPSPL_flush()


    Empties the storage, history and clears the current state.

    :rtype: void 











