<?php
namespace prggmr;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

use \Closure,
    \InvalidArgumentException,
    \prggmr\engine\signal as engine_signals;

/**
 * As of v0.3.0 the loop is now run in respect to the currently available handles,
 * this prevents the engine from running contionusly forever when there isn't anything
 * that it needs to do.
 *
 * To achieve this the engine uses routines for calculating when to run and 
 * shutdowns when no more are available.
 *
 * The Engine uses the State and Storage traits, and will also attempt to
 * gracefully handle exceptions when ENGINE_EXCEPTIONS is turned off.
 * 
 * The queue storage has also been improved in 0.3.0, previously the storage used
 * a non-index and index based storage, the storage now uses only a single array.
 */
class Engine {

    /**
     * Statefull object
     */
    use State, Storage;

    /**
     * Storage container node indices
     */
    const HASH_STORAGE = 0;
    const COMPLEX_STORAGE = 1;
    const INTERRUPT_STORAGE = 2;

    /**
     * Interuption Types
     */
    const INTERRUPT_PRE = 0;
    const INTERRUPT_POST = 1;

    /**
     * Last signal added to the engine.
     * 
     * @var  object
     */
    protected $_last_sig_added = null;

    /**
     * History of events
     * 
     * @var  array
     */
    protected $_history = [];

    /**
     * Current event in execution and hierachy
     * 
     * @var  object  \prggmr\Event
     */
    protected $_event = [];

    /**
     * Routine data.
     * 
     * @var  array
     */
    private $_routines = [];

    /**
     * Libraries loaded
     */
    protected $_module = [];

    /**
     * Throw exceptions encountered rather than a signal.
     *
     * @var  boolean
     */
    private $_engine_exceptions = null;

    /**
     * Signal registered for the engine exception signals.
     */
    private $_engine_handle_signal = null;

    /**
     * Currently executing signal and hierachy
     */
    private $_signal = [];

    /**
     * Starts the engine.
     *
     * @param  boolean  $event_history  Store a history of all events.
     * @param  boolean  $engine_exceptions  Throw an exception when a error 
     *                                      signal is triggered.
     * 
     * @return  void
     */
    public function __construct($event_history = true, $engine_exceptions = true)
    {
        $this->_engine_exceptions = (bool) $engine_exceptions;
        if ($event_history === false) {
            $this->_history = false;
        }
        $this->set_state(STATE_DECLARED);
        $this->flush();
        if ($this->_engine_exceptions) {
           $this->_register_error_handler();
        }
    }

    /**
     * Registers the engine error signal handler.
     *
     * @return  void
     */
    protected function _register_error_handler()
    {
        if (null === $this->_engine_handle_signal) {
            $this->_engine_handle_signal = new \prggmr\engine\signal\Engine_Errors();
        } else {
            $queue = $this->search_signals($this->_engine_handle_signal);
            if ($queue->count() !== 0) {
                return true;
            }
        }
        // TODO allow for specifing a context for the event rather than the 
        // event itself
        $engine = $this;
        $this->handle($this->_engine_handle_signal, function() use ($engine){
            $args = func_get_args();
            $exception = $engine->current_signal()->get_exception();
            if (null !== $exception) {
                $trace = array_reverse($exception->getTrace());
                $error = get_class_name($exception);
                $message = $exception->getMessage();
                $line = $exception->getLine();
                $file = $exception->getFile();
            } else {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $stack = array_pop($trace);
                $message = $engine->current_signal()->get_error();
                $error = get_class_name($engine->current_signal());
                $file = $stack['file'];
                $line = $stack['line'];
            }
            $stacktrace = '';
            $i=0;
            foreach ($trace as $_trace) {
                if (!isset($_trace['file']) 
                    || strpos($_trace['file'], PRGGMR_PATH) === false) {
                    $stacktrace .= sprintf(
                        $i.': # %s:%s(%s)'.PHP_EOL,
                        (isset($_trace['file'])) ? $_trace['file'] : '-',
                        (isset($_trace['line'])) ? $_trace['line'] : '-',
                        ((isset($_trace['class'])) 
                            ? $_trace['class'] . $_trace['type'] : '') 
                        . $_trace['function']
                    );
                    $i++;
                }
            }
            echo sprintf(
                'Exception: %s'.PHP_EOL.''
                .'Message: %s'.PHP_EOL.''
                .'Line: %s'.PHP_EOL.''
                .'File: %s'.PHP_EOL.''
                .'Trace:'.PHP_EOL.''
                .'%s',
                $error,
                $message,
                $line,
                $file,
                $stacktrace
            );
        });
    }

    /**
     * Disables the exception handler.
     *
     * @param  boolean  $history  Erase any history of exceptions signaled.
     *
     * @return  void
     */
    public function disable_signaled_exceptions($history = false)
    {
        $this->_engine_exceptions = false;
        if (null !== $this->_engine_handle_signal) {
            $this->delete_signal($this->_engine_handle_signal, $history);
        }
    }

    /**
     * Enables the exception handler.
     *
     * @return  void
     */
    public function enable_signaled_exceptions()
    {
        $this->_engine_exceptions = true;
        $this->_register_error_handler();
    }

    /**
     * Cleans out the event history.
     *
     * @return  void
     */
    public function erase_history()
    {
        if (false === $this->_history) {
            return;
        }
        $this->_history = [];
    }

    /**
     * Start the event loop.
     *
     * @todo unittest
     * 
     * @param  null|integer  $ttr  Number of milliseconds to run the loop.
     * 
     * @return  void
     */
    public function loop($ttr = null)
    {
        if (null !== $ttr) {
            $engine = $this;
            $this->handle(function() use ($engine) {
                $engine->shutdown();
            }, new \prggmr\module\time\Timeout($ttr));
        }
        $this->signal(new engine_signals\Loop_Start());
        while($this->_routine()) {
            // check state
            if ($this->get_state() === STATE_HALTED) break;
            $signals = $this->_routine->get_signals();
            if (count($signals) !== 0) {
                foreach ($signals as $_signal) {
                    $this->signal($_signal[0], $_signal[1]);
                }
            }
            $idle = $this->_routine->get_idle();
            // check for idle function
            if (false !== $idle) {
                $idle->idle($this);
            }
        }
        $this->signal(new engine_signals\Loop_Shutdown());
    }

    /**
     * Runs the complex signal routine for the engine loop.
     *
     * @todo unittest
     * 
     * @return  boolean|array
     */
    private function _routine()
    {
        $return = false;
        $this->_routine = new engine\Routine();
        // allow for external shutdown signal before running anything
        if ($this->get_state() === STATE_HALTED) return false;
        foreach ($this->_storage[self::COMPLEX_STORAGE] as $_key => $_node) {
            try {
                // Run the routine
                $_routine = $_node[0]->routine($this->_history);
                // Did it return true
                if (true === $_routine) {
                    $_routine = $_node[0]->get_routine();
                    // Is the routine a routine?
                    if (!$_routine instanceof signal\Routine) {
                        throw new \Exception(sprintf(
                            "%s did not return a routine",
                            get_class($_node[0])
                        ));
                    }
                    // Get all required data and reset the routine
                    $_signals = $_routine->get_signals();
                    $_idle = $_routine->get_idle();
                    $_routine->reset();
                    // Check signals
                    if (null !== $_signals && count($_signals) != 0) {
                        foreach ($_signals as $__signal) {
                            list($__sig, $__event) = $__signal;
                            // ensure it has not exhausted
                            if (false === $this->has_signal_exhausted($__sig)) {
                                $return = true;
                                $this->_routine->add_signal($__sig, $__event);
                            }
                        }
                    }
                    // Check for an idle function
                    if (null !== $_idle) {
                        $return = true;
                        $this->_routine->add_idle($_idle);
                    }
                }
            // Catch any problems that happended and signal them
            } catch (\Exception $e) {
                $this->signal(new engine_signals\Routine_Calculation_Error(
                    "An error has occured during a routine calculation"
                ),  new engine\event\Error([$e, $_node]));
            }
        }
        return $return;
    }

    /**
     * Returns the current routine object.
     *
     * @todo unittest
     *
     * @return  null|object
     */
    public function get_routine(/* ... */)
    {
        return $this->_routine;
    }

    /**
     * Determines if the given signal has exhausted.
     * 
     * @param  string|integer|object  $queue
     * 
     * @return  boolean
     */
    public function has_signal_exhausted($signal)
    {
        $queue = $this->search_signals($signal);
        if (null === $queue) {
            return true;
        }
        return true === $this->queue_exhausted($queue);
    }

    /**
     * Determine if all queue handles are exhausted.
     *
     * @param  object  $queue  \prggmr\Queue
     * 
     * @return  boolean
     */
    public function queue_exhausted($queue)
    {
        if ($queue->count() === 0) {
            return true;
        }
        $queue->reset();
        while($queue->valid()) {
            // if a non exhausted handle is found return false
            if (!$queue->current()[0]->is_exhausted()) {
                return false;
            }
            $queue->next();
        }
        return true;
    }

    /**
     * Removes a signal handler.
     *
     * @param  mixed  $signal  Signal instance or signal.
     * @param  mixed  $handle  Handle instance or identifier.
     * 
     * @return  void
     */
    public function handle_remove($signal, $handle)
    {
        $queue = $this->search_signals($signal);
        if (null === $queue) {
            return;
        }
        return $queue->dequeue($handle);
    }

    /**
     * Empties the storage, history and clears the current state.
     *
     * @return void
     */
    public function flush(/* ... */)
    {
        $this->_storage = [[], [], []];
        if (false !== $this->_history){
            $this->_history = [];
        }
        $this->set_state(STATE_DECLARED);
    }

    /**
     * Registers an object listener.
     *
     * @param  object  $listener  prggmr\Listener
     *
     * @return  void
     */
    public function listen(Listener $listener)
    {
        foreach ($listener->_get_signals() as $_signal) {
            $this->handle($_signal, [$listener, $_signal]);
        }
    }

    /**
     * Creates a new signal handler.
     *
     * @param  string|int|object  $signal  Signal to attach the handle.
     * @param  object  $callable  Signal handler
     *
     * @return  object|boolean  Handle, boolean if error
     */
    public function handle($signal, $handle)
    {
        if (!$handle instanceof Handle) {
            if (!is_callable($handle)) {
                $this->signal(new engine_signals\Invalid_Handle(
                       "Invalid handle given to the handle method" 
                    ), new engine\event\Error([func_get_args()])
                );
                return false;
            }
            $handle = new Handle($handle);
        }
        $queue = $this->register($signal);
        if (false !== $queue) {
            if (is_array($queue)) {
                $queue = $queue[0][0];
            }
            $queue->enqueue($handle, $handle->get_priority());
        }
        return $handle;
    }

    /**
     * Registers or locates a signal queue in storage.
     *
     * Queues are stored using an array structure in the storage of
     *
     * [
     *     0 => prggmr\Signal,
     *     1 => prggmr\Queue
     * ]
     *
     * @param  string|integer|object  $signal  Signal
     *
     * @return  boolean|object  false|prggmr\Queue
     */
    public function register($signal)
    {
        $queue = false;

        if (!$signal instanceof \prggmr\signal\Standard) {
            try {
                $signal = new Signal($signal);
            } catch (\InvalidArgumentException $e) {
                $this->signal(new engine_signals\Invalid_Signal(
                    "Invalid signal given to register"
                ),  new engine\event\Error([$exception, $signal]));
                return false;
            }
        }

        $search = $this->search_signals($signal);

        if (null !== $search) {
            return $search;
        }

        if (!$queue) {
            $queue = new Queue();
            if (!$signal instanceof \prggmr\signal\Complex) {
                $this->_storage[self::HASH_STORAGE][(string) $signal->get_info()] = [
                    $signal, $queue
                ];
            } else {
                $id = spl_object_hash($signal);
                $this->_storage[self::COMPLEX_STORAGE][$id] = [$signal, $queue];
            }
        }
        $this->_last_sig_added = $signal;
        return $queue;
    }

    /**
     * Searches for a signal in storage returning its storage queue if found,
     * optionally the index can be returned.
     * 
     * @param  string|int|object  $signal  Signal to search for.
     * @param  boolean  $index  Return the index of the signal.
     * 
     * @return  null|object  null|Queue
     */
    public function search_signals($signal, $index = false) 
    {
        if ($signal instanceof \prggmr\signal\Complex) {
            $id = spl_object_hash($signal);
            if (isset($this->_storage[self::COMPLEX_STORAGE][$id])) {
                if ($index) return $id;
                return $this->_storage[self::COMPLEX_STORAGE][$id][1];
            }
            return null;
        }
        if ($signal instanceof \prggmr\Signal) {
            $signal = $signal->get_info();
        }
        $signal = (string) $signal;
        if (isset($this->_storage[self::HASH_STORAGE][$signal])) {
            if ($index) return $signal;
            return $this->_storage[self::HASH_STORAGE][$signal][1];
        }
        return null;
    }

    /**
     * Runs the evaluation for the registered complex signals using the given
     * signal.
     *
     * @param  string|object|int  $signal  Signal to evaluate
     *
     * @return  array|null  [[[signal, queue], eval_return]]
     */
    public function evaluate_signals($signal)
    {
        if (count($this->_storage[self::COMPLEX_STORAGE]) == 0) {
            return null;
        }
        $return = [];
        foreach ($this->_storage[self::COMPLEX_STORAGE] as $_node) {
            $eval = $_node[0]->evaluate($signal);
            if (false !== $eval) {
                $return[] = [$_node, $eval];
            }
        }
        if (count($return) !== 0) {
            return $return;
        }
        return null;
    }

    /**
     * Loads an event for the current signal.
     * 
     * @param  int|string|object  $signal
     * @param  object  $event  \prggmr\Event
     * @param  int|null  $ttl  Event TTL
     * 
     * @return  object  \prggmr\Event
     */
    private function _event($signal, $event = null, $ttl = null)
    {
        // event creation
        if (!$event instanceof Event) {
            if (null !== $event) {
                $this->signal(new engine_signals\Invalid_Event(
                    "Invalid event passed for execution"
                ),  new engine\event\Error($event));
            }
            $event = new Event($ttl);
        } else {
            if ($event->get_state() !== STATE_DECLARED) {
                $event->set_state(STATE_RECYCLED);
            }
        }
        // keep track of the current event
        $this->_event[] = $event;
        // are we keeping the history
        if (false === $this->_history) {
            return $event;
        }
        // event history management
        if (count($this->_event) > 1)  {
            $event->set_parent($this->current_event(-1));
        }
        $this->_history[] = [$event, $signal, milliseconds()];
        return $event;
    }

    /**
     * Exits the event from the engine.
     * 
     * @param  object  $event  \prggmr\Event
     */
    private function _event_exit($event)
    {
        // event execution finished cleanup state if clean
        if ($event->get_state() === STATE_RUNNING) {
            $event->set_state(STATE_EXITED);
        }
        // are we keeping the history
        if (!$this->_history) {
            return null;
        }
        if (count($this->_event) !== 0) {
            $this->_current_event = array_pop($this->_event);
        } else {
            $this->_current_event = null;
        }
    }

    /**
     * Signals an event.
     *
     * @param  mixed  $signal  Signal instance or signal.
     *
     * @param  object  $event  \prggmr\Event
     *
     * @return  object  Event
     */
    public function signal($signal, $event = null, $ttl = null)
    {
        // store engine signal
        $this->_signal[] = $signal;
        // load engine event
        $event = $this->_event($signal, $event, $ttl);
        // locate sig handlers
        $queue = new Queue();
        // purge exhausted queues
        if (PRGGMR_PURGE_EXHAUSTED) {
            $queues = [];
        }
        // search for exact matches
        $searched = $this->search_signals($signal);
        if (null !== $searched) {
            $queue->merge($searched->storage());
            if (PRGGMR_PURGE_EXHAUSTED) {
                $queues[] = $searched;
            }
        }

        // evaluate complex signals
        $evalated = $this->evaluate_signals($signal);
        if (null !== $evalated) {
            array_walk($evalated, function($node) use ($queue, $queues) {
                if (is_bool($node[1]) === false) {
                    $data = $node[1];
                    if (is_array($data)) {
                        foreach ($data as $_k => $_v) {
                            $event->{$_k} = $_v;
                        }
                    }
                }
                $queue->merge($node[0][1]->storage());
                if (PRGGMR_PURGE_EXHAUSTED) {
                    $queues[] = $node[0][1];
                }
            });
        }

        // execute the signal
        $this->_execute($signal, $queue, $event);

        // purge exhausted handles
        if (PRGGMR_PURGE_EXHAUSTED) {
            foreach ($queues as $_queue) {
                foreach ($_queue->storage() as $_node) {
                    if ($_node[0]->is_exhausted()) {
                        $_queue->dequeue($_node[0]);
                    }
                }
            }
        }
        // Remove the last signal
        array_pop($this->_signal);
        return $event;
    }

    /**
     * Executes a queue.
     * 
     * This will monitor the event status and break on a HALT or ERROR state.
     * 
     * Executes interruption functions before and after queue execution.
     *
     * @param  object  $signal  Signal instance.
     * @param  object  $queue  Queue instance.
     * @param  object  $event  Event instance.
     * @param  boolean  $interupt  Run the interrupt functions.
     *
     * @return  void
     */
    private function _execute($signal, $queue, $event, $interrupt = true)
    {
        if ($event->has_expired()) {
            $this->signal(new engine_signals\Event_Expired(
                "Event has expired"
            ),  new engine\event\Error([$event]));
            return $event;
        }
        // handle pre interupt functions
        if ($interrupt) {
            $this->_interrupt($signal, self::INTERRUPT_PRE, $event);
        }
        // execute the Queue
        $this->_queue_execute($queue, $event);
        // handle interupt functions
        if ($interrupt) {
            $this->_interrupt($signal, self::INTERRUPT_POST, $event);
        }
        $this->_event_exit($event);
    }

    /**
     * Executes a queue.
     *
     * If PRGGMR_EXHAUSTION_PURGE is true handles will be purged once they 
     * reach exhaustion.
     *
     * @param  object  $queue  prggmr\Queue
     * @param  object  $event  prggmr\Event
     *
     * @return  void
     */
    private function _queue_execute($queue, $event)
    {
        // execute sig handlers
        $queue->sort();
        reset($queue->storage());
        foreach ($queue->storage() as $_node) {
            $_handle = $_node[0];
            # Always check state first
            if ($event->get_state() === STATE_HALTED) {
                continue;
            }
            # test for exhaustion
            if ($_handle->is_exhausted()) {
                continue;
            }
            $_handle->decrement_exhaust();
            $result = null;
            if (!$this->_engine_exceptions) {
                $result = $this->_func_exec(
                    $_handle->get_function(),
                    $event
                );
            } else {
                try {
                    $result = $this->_func_exec(
                        $_handle->get_function(),
                        $event
                    );
                } catch (\Exception $exception) {
                    echo $error;
                    $event->set_state(STATE_ERROR);
                    // We hit a recursive loop
                    if ($exception instanceof Engine_Exception) {
                        throw $exception;
                    }
                    $this->signal(new engine_signals\Handle_Exception(
                        "Exception occured during handle execution"
                    ),  new engine\event\Error([$exception, $event]));
                }
            }
            if (null !== $result) {
                $event->set_result($result);
                if (false === $result) {
                    $event->halt();
                }
            }
        }
    }

    /**
     * Executes a callable engine function.
     *
     * @param  callable  $function  Function to execute
     * @param  object  $event  Event context to execute within
     * 
     * @return  boolean
     */
    private function _func_exec($function, $event)
    {
        if ($function instanceof \Closure) {
            $func = $function->bindTo($event, null);
            return $func();
        }
        if (count($function) >= 2) {
            $class = new $function[0];
            return $class->$function[1]($event);
        }
        return $function[0]($event);
    }
    
    

    /**
     * Retrieves the event history.
     * 
     * @return  array
     */
    public function event_history(/* ... */)
    {
        return $this->_history;
    }

    /**
     * Sends the engine the shutdown signal.
     *
     * @return  void
     */
    public function shutdown()
    {
        $this->set_state(STATE_HALTED);
    }

    /**
     * Returns a json encoded array of the event history.
     *
     * @return  string
     */
    public function event_analysis(/* ... */)
    {
        if (!$this->_store_history) return false;
        return json_encode($this->_event_history());
    }

    /**
     * Loads a prggmr module.
     * 
     * @param  string  $name  Module name.
     * @param  string|null  $dir  Location of the module. 
     * 
     * @return  boolean
     */
    public function load_module($name, $dir = null) 
    {
        // already loaded
        if (isset($this->_module[$name])) return true;
        if ($dir === null) {
            $dir = PRGGMR_MODULE_DIR;
        } else {
            if (!is_dir($dir)) {
                $this->signal(new engine_signals\Signal_Library_Failure(sprintf(
                    "Module directory %s does not exist", $dir
                )),  new engine\event\Error($dir));
            }
        }
        if (is_dir($dir.'/'.$name)) {
            $path = $dir.'/'.$name;
            if (file_exists($path.'/__autoload.php')) {
                // keep history of what has been loaded
                $this->_module[$name] = true;
                require $path.'/__autoload.php';
            } else {
                $this->signal(new engine_signals\Signal_Library_Failure(
                    "Module does not have an __autoload file"
                ),  new engine\event\Error([$name, $dir]));
            }
        } else {
            $this->signal(new engine_signals\Signal_Library_Failure(sprintf(
                "Module %s does not exist", $name
            )), new engine\event\Error());
        }
        return true;
    }

    /**
     * Registers a function to interrupt the signal stack before a signal fires,
     * allowing for manipulation of the event beore it is passed to handles.
     *
     * @param  string|object  $signal  Signal instance or class name
     * @param  object  $handle  Handle to execute
     * 
     * @return  boolean  True|False false is failure
     */
    public function before($signal, $handle)
    {
        return $this->_signal_interrupt($signal, $handle, self::INTERRUPT_PRE);
    }

    /**
     * Registers a function to interrupt the signal stack after a signal fires,
     * allowing for manipulation of the event after it is passed to handles.
     *
     * @param  string|object  $signal  Signal instance or class name
     * @param  object  $handle  Handle to execute
     * 
     * @return  boolean  True|False false is failure
     */
    public function after($signal, $handle)
    {
        return $this->_signal_interrupt($signal, $handle, self::INTERRUPT_POST);
    }

    /**
     * Registers a function to interrupt the signal stack before or after a 
     * signal fires.
     *
     * @param  string|object  $signal
     * @param  object  $handle  Handle to execute
     * @param  int|null  $place  Interuption location. INTERUPT_PRE|INTERUPT_POST
     * 
     * @return  boolean  True|False false is failure
     */
    protected function _signal_interrupt($signal, $handle, $interrupt = null) 
    {
        // Variable Checks
        if (!$handle instanceof Handle) {
            if (!is_callable($handle)) {
                $this->signal(new engine_signals\Invalid_Handle(
                    "Invalid handle given for signal interruption"
                ),  new engine\event\Error($handle));
                return false;
            } else {
                $handle = new Handle($handle);
            }
        }
        if (!is_object($signal) && !is_int($signal) && !is_string($signal)) {
            $this->signal(new engine_signals\Ivalid_Signal(
                "Invalid signal given for signal interruption"
            ), new engine\event\Error($signal));
            return false;
        }
        if (null === $interrupt) {
            $interrupt = self::INTERRUPT_PRE;
        }
        if ($interrupt != self::INTERRUPT_PRE && 
            $interrupt != self::INTERRUPT_POST) {
            $this->signal(new engine_signals\Invalid_Interrupt(
                "Invalid interruption location"
            ), new engine\event\Error($interrupt));
        }
        if (!isset($this->_storage[self::INTERRUPT_STORAGE][$interrupt])) {
            $this->_storage[self::INTERRUPT_STORAGE][$interrupt] = [[], []];
        }
        $storage =& $this->_storage[self::INTERRUPT_STORAGE][$interrupt];
        if ($signal instanceof signal\Complex) {
            $storage[self::COMPLEX_STORAGE][] =  [
                $signal, $handle
            ];
        } else {
            if ($signal instanceof Signal) {
                $name = $signal->get_info();
            } else {
                if (is_object($signal)) {
                    $name = get_class($signal);
                } else {
                    $name = $signal;
                }
            }
            if (!isset($storage[self::HASH_STORAGE][$name])) {
                $storage[self::HASH_STORAGE][$name] = [];
            }
            $storage[self::HASH_STORAGE][$name][] = [
                $signal, $handle
            ];
        }
        return true;
    }

    /**
     * Handle signal interuption functions.
     * 
     * @param  object  $signal  Signal
     * @param  int  $interupt  Interupt type
     * 
     * @return  boolean
     */
    private function _interrupt($signal, $type, $event)
    {
        // do nothing no interrupts registered
        if (!isset($this->_storage[self::INTERRUPT_STORAGE][$type])) {
            return true;
        }
        $queue = null;
        if (count($this->_storage[self::INTERRUPT_STORAGE][$type][self::COMPLEX_STORAGE]) != 0) {
            foreach ($this->_storage[self::INTERRUPT_STORAGE][$type][self::COMPLEX_STORAGE] as $_node) {
                $eval = $_node[0]->evaluate($signal);
                if (false !== $eval) {
                    if (true !== $eval) {
                        if (is_array($eval)) {
                            foreach ($eval as $_k => $_v) {
                                $event->{$_k} = $_v;
                            }
                        }
                    }
                    if (null === $queue) {
                        $queue = new Queue();
                    }
                    if (!$_node[1]->is_exhausted()) {
                        $queue->enqueue($_node[1], $_node[1]->get_priority());
                    }
                }
            }
        }
        $lookup = [];
        $class_name = (is_object($signal)) ? get_class($signal) : $signal;
        if ($signal instanceof Signal) {
            $info = $signal->get_info();
            if ($info != $class_name) {
                $lookup[] = $info;
            }
        } else {
            $lookup[] = $class_name;
        }
        foreach ($lookup as $_index) {
            if (isset($this->_storage[self::INTERRUPT_STORAGE][$type][self::HASH_STORAGE][$_index])) {
                foreach ($this->_storage[self::INTERRUPT_STORAGE][$type][self::HASH_STORAGE][$_index] as $_node) {
                    if (null === $queue) {
                        $queue = new Queue();
                    }
                    if (!$_node[1]->is_exhausted()) {
                        $queue->enqueue($_node[1], $_node[1]->get_priority());
                    }
                }
            }
        }
        if (null !== $queue) {
            $this->_queue_execute($queue, $event);
        }
    }

    /**
     * Cleans any exhausted signals from the engine.
     * 
     * @param  boolean  $history  Erase any history of the signals cleaned.
     * 
     * @return  void
     */
    public function clean($history = false)
    {
        $storages = [
            self::HASH_STORAGE, self::COMPLEX_STORAGE, self::INTERRUPT_STORAGE
        ];
        foreach ($storages as $_storage) {
            if (count($this->_storage[$_storage]) == 0) continue;
            foreach ($this->_storage[$_storage] as $_index => $_node) {
                if ($_node[1] instanceof Handle && $_node[1]->is_exhausted() ||
                    $_node[1] instanceof Queue && $this->queue_exhausted($_node[1])) {
                    unset($this->_storage[$_storage][$_index]);
                    if ($history) {
                        $this->erase_signal_history(
                            ($_node[0] instanceof signal\Complex) ?
                                $_node[0] : $_node[0]->get_info()
                        );
                    }
                }
            }
        }
    }

    /**
     * Delete a signal from the engine.
     * 
     * @param  string|object|int  $signal  Signal to delete.
     * @param  boolean  $history  Erase any history of the signal.
     * 
     * @return  boolean
     */
    public function delete_signal($signal, $history = false)
    {
        $info = false;
        if ($signal instanceof signal\Standard) {
            if ($signal instanceof signal\Complex) {
                $obj = spl_object_hash($signal);
                if (!isset($this->_storage[self::COMPLEX_STORAGE][$obj])) {
                    return false;
                }
                unset($this->_storage[self::COMPLEX_STORAGE][$obj]);
            } else {
                $info = $signal->get_info();
            }
        } else {
            if (!is_string($signal) && !is_int($signal)) {
                $this->signal(new engine_signals\Invalid_Signal(
                    "Delete signal"
                ), new engine\event\Error($signal));
                return false;
            }
            $info = $signal;
        }

        if (false !== $info) {
            if (!isset($this->_storage[self::HASH_STORAGE][$info])) {
                return false;
            }
            unset($this->_storage[self::HASH_STORAGE][$info]);
        }

        if ($history) {
            $this->erase_signal_history($signal);
        }
        return true;
    }

    /**
     * Erases any history of a signal.
     * 
     * @param  string|object  $signal  Signal to be erased from history.
     * 
     * @return  void
     */
    public function erase_signal_history($signal)
    {
        if (!$this->_history) {
            return false;
        }
        // recursivly check if any events are a child of the given signal
        // because if the chicken doesn't exist neither does the egg ...
        // or does it?
        $descend_destory = function($_event, $_signal) use ($signal, &$descend_destory) {
            // child and not a child of itself
            if ($_event->is_child() && $_event->get_parent() !== $_event) {
                return $descend_destory($_event->get_parent(), $_signal);
            }
            if ($_signal === $signal) {
                return true;
            }
        };
        foreach ($this->_history as $_key => $_node) {
            if ($_node[1] === $signal) {
                unset($this->_history[$_key]);
            } elseif ($_node[0]->is_child() && $_node[0]->get_parent() !== $_node[0]) {
                if ($descend_destory($_node[0]->get_parent(), $_node[1])) {
                    unset($this->_history[$_key]);
                }
            }
        }
    }

    /**
     * Sets the flag for storing the event history.
     *
     * Note that this will delete the current if reset.
     *
     * @param  boolean  $flag
     *
     * @return  void
     */
    public function save_event_history($flag)
    {
        if ($flag === true) {
            if (!$this->_history) {
                $this->_history = [];
            }
            return;
        }
        $this->_history = false;
    }

    /**
     * Returns the current signal in execution.
     *
     * @param  integer  $offset  In memory hierarchy offset +/-.
     *
     * @return  object
     */
    public function current_signal($offset = 1)
    {
        $count = count($this->_signal);
        if ($count === 0) {
            return null;
        }
        if ($count === 1) {
            return $this->_signal[0];
        }
        return array_slice($this->_signal, $offset, 1)[0];
    }

    /**
     * Returns the current event.
     *
     * @param  integer  $offset  In memory hierarchy offset +/-.
     *
     * @return  object  \prggmr\Event
     */
    public function current_event($offset = 0)
    {
        $count = count($this->_event);
        if ($count === 0) {
            return null;
        }
        if ($count === 1) {
            return $this->_event[0];
        }
        return array_slice($this->_event, $offset, 1)[0];
    }
}

class Engine_Exception extends \Exception {

    /**
     * The signal that occured.
     * 
     * @var  object
     */
    protected $_signal = null;

    /**
     * Arguments associated with the exception
     * 
     * @var  null|array
     */
    protected $_args = null;

    /**
     * Constructs a new engine exception.
     * 
     * @param  string|null  $message  Exception message if given
     * @param  object  $signal  Error signal
     * @param  array  $args  Arguments present for exception
     */
    public function __construct($message, $signal, $args)
    {
        parent::__construct($message);
        $this->_signal = $signal;
        $this->_args = $args;
    }

    /**
     * Returns exception arguments.
     * 
     * @return  array
     */
    public function get_args(/* ... */)
    {
        return $this->_args;
    }

    /**
     * Returns engine exception code.
     * 
     * @return  integer
     */
    public function get_signal(/* ... */)
    {
        return $this->_signal;
    }
}