<?php
namespace prggmr;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Creates a new signal handler.
 *
 * @param  object  $callable  Closure
 * @param  string|integer|object  $signal  Signal to attach the handle.
 * @param  integer $priority  Handle priority.
 * @param  integer  $exhaust  Handle exhaustion.
 *
 * @return  object|boolean  Handle, boolean if error
 */
function handle($closure, $signal = null, $priority = QUEUE_DEFAULT_PRIORITY, $exhaust = 1)
{
    return \prggmr::instance()->handle($closure, $signal, $priority, $exhaust);
}

/**
 * Remove a signal handler.
 *
 * @param  object  $handle  Handle instance.
 * @param  string|integer|object  $signal  Signal handle is attached to.
 *
 * @return  void
 */
function handle_remove($handle, $signal)
{
    return \prggmr::instance()->handle_remove($handle, $signal);   
}

/**
 * Registers a new signal handle loader which recursively loads files in the
 * given directory when a signal is triggered.
 * 
 * @param  integer|string|object  $signal  Signal to register with
 * @param  string  $directory  Directory to load handles from
 * @param  integer  $heap  Queue heap type
 * 
 * @return  object  \prggmr\Handle
 */
function handle_loader($signal, $directory, $heap = QUEUE_MIN_HEAP)
{
    return \prggmr::instance()->handle_loader($signal, $directory, $heap);
}

/**
 * Signal an event.
 *
 * @param  string|integer|object  $signal  Signal or a signal instance.
 * @param  array  $vars  Array of variables to pass the handles.
 * @param  object  $event  Event
 *
 * @return  object  \prggmr\Event
 */
function signal($signal, $vars = null, &$event = null)
{
    return \prggmr::instance()->signal($signal, $vars, $event);
}

/**
 * Returns the event history.
 * 
 * @return  array
 */
function event_history(/* ... */)
{
    return \prggmr::instance()->event_history();
}

/**
 * Locates or creates a signal Queue in storage.
 * 
 * @param  string|integer|object  $signal  Signal
 * @param  boolean  $create  Create the queue if not found.
 * @param  integer  $type  [QUEUE_MIN_HEAP,QUEUE_MAX_HEAP]
 *
 * @return  boolean|array  False|[QUEUE_NEW|QUEUE_EMPTY|QUEUE_NONEMPTY, queue, signal]
 */
function signal_queue($signal, $create = true, $type = QUEUE_MIN_HEAP)
{
    return \prggmr::instance()->signal_queue($signal, $create, $type);
}

/**
 * Starts the prggmr event loop.
 *
 * @param  null|integer  $ttr  Number of milliseconds to run the loop. 
 *
 * @return  void
 */
function loop($ttr = null)
{
    return \prggmr::instance()->loop($ttr);
}

/**
 * Sends the loop the shutdown signal.
 *
 * @return  void
 */
function shutdown()
{
    return \prggmr::instance()->shutdown();
}

/**
 * Load a signal library.
 * 
 * @param  string  $name  Signal library name.
 * @param  string|null  $dir  Location of the library. 
 * 
 * @return  void
 */
function load_signal($name, $dir = null) 
{
    return \prggmr::instance()->load_signal($name, $dir);
}

/**
 * Registers a function to interupt the signal stack before or after a 
 * signal fires.
 * 
 * @param  object  $handle  Handle to execute
 * @param  string|object  $signal
 * @param  int|null  $place  Interruption location. prggmr\Engine::INTERRUPT_PRE|prggmr\Engine::INTERRUPT_POST
 * @param  int|null  $priority  Interrupt priority
 * @param  boolean  $complex  Register the given complex signal as a complex interrupt signal
 * 
 * @return  boolean  True|False false is failure
 */
function signal_interrupt($handle, $signal, $interrupt = null, $priority = null, $complex = false) 
{
    return \prggmr::instance()->signal_interrupt($handle, $signal, $interrupt, $priority, $complex);
}

/**
 * Returns the prggmr object instance.
 * 
 * @return  object  prggmr\Engine
 */
function prggmr()
{
    return \prggmr::instance();
}

/**
 * Cleans any exhausted signal queues from the engine.
 * 
 * @param  boolean  $history  Erase any history of the signals cleaned.
 * 
 * @return  void
 */
function clean($history = false)
{
    return \prggmr::instance()->clean($history);
}

/**
 * Delete a signal from the engine.
 * 
 * @param  string|object|int  $signal  Signal to delete.
 * @param  boolean  $history  Erase any history of the signal.
 * 
 * @return  boolean
 */
function delete_signal($signal, $history = false)
{
    return \prggmr::instance()->delete_signal($storage, $history);
}

/**
 * Erases any history of a signal.
 * 
 * @param  string|object  $signal  Signal to be erased from history.
 * 
 * @return  void
 */
function erase_signal_history($signal)
{
    return \prggmr::instance()->erase_signal_history($signal);
}

/**
 * Initialize the prggmr global engine.
 *
 * @param  boolean  $event_history  Store a history of all events.
 * @param  boolean  $engine_exceptions  Throw an exception when a error 
 *                                      signal is triggered.
 * 
 * @return  object  prggmr\Engine
 */
function init($event_history = true, $engine_exceptions = true)
{
    return \prggmr::init($event_history, $engine_exceptions);
}

/**
 * Disables the exception handler.
 *
 * @param  boolean  $history  Erase any history of exceptions signaled.
 *
 * @return  void
 */
function disable_signaled_exceptions($history = false)
{
    return \prggmr::instance()->disable_signaled_exceptions($history);
}

/**
 * Enables the exception handler.
 *
 * @return  void
 */
function enable_signaled_exceptions()
{
    return \prggmr::instance()->enable_signaled_exceptions();
}

/**
 * Cleans out the event history.
 *
 * @return  void
 */
function erase_history()
{
    return \prggmr::instance()->erase_history();
}

/**
 * Sets the flag for storing the event history.
 * If disabling the history this does not clear the current.
 *
 * @param  boolean  $flag
 *
 * @return  void
 */
function save_event_history($flag)
{
    return \prggmr::instance()->save_event_history($flag);
}