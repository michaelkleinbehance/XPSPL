<?php
namespace prggmr;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

use \Closure,
    \InvalidArgumentException,
    \prggmr\engine\Signals as engine_signals;

/**
 * Complex signal return to trigger the signal during routine calculation.
 */
define('ENGINE_ROUTINE_SIGNAL', -0xF14E);

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
     * Returns of calling queue.
     * 
     * QUEUE_NEW
     * A new empty queue was created.
     * 
     * QUEUE_EMPTY
     * An empty queue was found.
     * 
     * QUEUE_NONEMPTY
     * A non-empty queue was found.
     */
    const QUEUE_NEW = 0xA01;
    const QUEUE_EMPTY = 0xA02;
    const QUEUE_NONEMPTY = 0xA03;

    /**
     * Search Results
     * 
     * SEARCH_NULL
     * Found no results
     * 
     * SEARCH_FOUND
     * Found a single result
     * 
     * SEARCH_NOOP
     * Search is non-operational (looking for non-searchable)
     */
    const SEARCH_NULL = 0xA04;
    const SEARCH_FOUND = 0xA05;
    const SEARCH_NOOP = 0xA06;

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
     * Last sig handler added to the engine.
     * 
     * @var  object
     */
    protected $_last_sig_added = null;

    /**
     * History of events
     * 
     * @var  array
     */
    protected $_event_history = [];

    /**
     * Current event in execution
     * 
     * @var  object  \prggmr\Event
     */
    protected $_current_event = null;

    /**
     * Number of recursive event calls
     * 
     * @var  integer
     */
    protected $_event_recursive = 0;

    /**
     * Event children
     * 
     * @var  array
     */
    protected $_event_children = [];

    /**
     * Routine data.
     * 
     * @var  array
     */
    private $_routines = [];

    /**
     * Libraries loaded
     */
    protected $_libraries = [];

    /**
     * Throw exceptions encountered rather than a signal.
     *
     * @var  boolean
     */
    private $_engine_exceptions = null;

    /**
     * Maintain the event history.
     *
     * @var  boolean
     */
    private $_store_history = null;

    /**
     * Signal registered for the engine exception signals.
     */
    private $_engine_handle_signal = null;

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
        $this->_store_history = (bool) $event_history;
        $this->flush();
        if ($this->_engine_exceptions) {
           $this->_register_exception_handler();
        }
    }

    /**
     * Registers the engine exceptions signal handler.
     *
     * @return  void
     */
    protected function _register_exception_handler()
    {
        $this->load_signal('integer');
        if (null === $this->_exception_handle_signal) {
            $this->_engine_handle_signal = new \prggmr\signal\integer\Range(
                0xE002, 0xE014
            );
        } else {
            if ($this->_search_complex($this->_engine_handle_signal)[0] === self::SEARCH_FOUND) {
                return true;
            }
        }
        $this->handle(function(){
            $args = func_get_args();
            $message = null;
            $type = $this->get_signal();
            if ($args[0] instanceof \Exception) {
                $message = $args[0]->getMessage();
            } else {
                $message = engine_code($type);
            }
            throw new EngineException($message, $typw, $args);
        }, $this->_engine_handle_signal, 0, null);
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
        $this->_register_exception_handler();
    }

    /**
     * Cleans out the event history.
     *
     * @return  void
     */
    public function erase_history()
    {
        $this->_event_history = [];
    }

    /**
     * Start the event loop.
     * 
     * @param  null|integer  $ttr  Number of milliseconds to run the loop.
     * 
     * @return  void
     */
    public function loop($ttr = null)
    {
        if (null !== $ttr) {
            $this->handle(function($engine){
                $engine->shutdown();
            }, new \prggmr\signal\time\Timeout($ttr, $this));
        }
        $this->signal(engine_signals::LOOP_START);
        while($this->_routines()) {
            // check state
            if ($this->get_state() === STATE_HALTED) break;
            if (count($this->_routines[0]) !== 0) {
                var_dump($this);
                exit;
                foreach ($this->_routines[0] as $_routine) {
                    $this->signal($_routine[0], $_routine[1], $_routine[2]);
                }
            }
            // check for idle function
            if ($this->_routines[2] !== null) {
                call_user_func_array($this->_routines[2], [$this]);
            }
            // check for idle time
            if ($this->_routines[1][0] !== null && $this->_routines[1][1] > milliseconds()) {
                // idle for the given time in milliseconds
                usleep($this->_routines[1][0] * 1000);
            }
        }
        $this->signal(engine_signals::LOOP_SHUTDOWN);
    }

    /**
     * Runs complex signal routines for engine loop.
     *
     * The routines are stored within the engine using the following structure,
     *
     * [
     *     # Signals to dispatch
     *     0 => [],
     *     # Idle Time
     *     1 => [
     *         # Time to idle
     *         0 => (int|null)
     *         # Timestamp when the engine should wake up
     *         1 => int
     *     ],
     *     # Idle function
     *     2 => (null|closure)
     * ]
     *
     * The engine only allows for a single function to be executed as the 
     * idle function and attempting to register two or more functions will
     * result in a engine\Signal::IDLE_FUNCTION_OVERFLOW signal triggered.
     * 
     * @return  boolean|array
     */
    private function _routines()
    {
        $return = false;
        $this->_routines = [[], [0], null];
        // allow for external shutdown signal before running anything
        if ($this->get_state() === STATE_HALTED) return false;
        foreach ($this->_storage[self::COMPLEX_STORAGE] as $_key => $_node) {
            try {
                // Run the routine
                $_routine = $_node[0]->routine($this->_event_history);
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
                    $_idle = $_routine->get_idle_time();
                    $_function = $_routine->get_idle_function();
                    $_routine->reset();
                    // Check signals
                    if (null !== $_signals && count($_signals) != 0) {
                        foreach ($_signals as $__signal) {
                            list($__sig, $__vars, $__event) = $__signal;
                            // ensure it has not exhausted
                            if (false === $this->has_signal_exhausted($__sig)) {
                                $return = true;
                                // As of v2.0.0 the engine no longer attempts to keep
                                // a reference to the same event.
                                // This functionality is now dependent upon the signal
                                $this->_routines[0][] = [$__sig, $__vars, $__event];
                            }
                        }
                    }
                    // Idle Time
                    if ($_idle !== null && (is_int($_idle) || is_float($_idle))) {
                        if (0 === $this->_routines[1][0] || $this->_routines[1][0] > $_idle) {
                            $return = true;
                            $this->_routines[1] = [$_idle, $_idle + milliseconds()];
                        }
                    }
                    // Idle function
                    if ($_function !== null) {
                        if ($this->_routines[2] !== null) {
                            $this->signal(engine_signals::IDLE_FUNCTION_OVERFLOW, array($_node[0]));
                        } else {
                            $this->_routines[2] = $_function;
                        }
                    }
                }
            // Catch any problems that happended and signal them
            } catch (\Exception $e) {
                $this->signal(engine_signals::ROUTINE_CALCUATION_ERROR, [$e, $_node]);
            }
        }
        return $return;
    }

    /**
     * Determines if the given signal has exhausted during routine calculation.
     * 
     * @param  string|integer|object  $queue
     * 
     * @return  boolean
     */
    public function has_signal_exhausted($signal)
    {
        $queue = $this->signal_queue($signal, false);
        if (false === $queue) return true;
        // if (true === $this->queue_exhausted($queue)) {
        //     $this->signal(engine_signals::EXHAUSTED_QUEUE_SIGNALED, array(
        //         $queue
        //     ));
        //     return true;
        // }
        return true === $this->queue_exhausted($queue);
    }

    /**
     * Analysis a queue to determine if all handles are exhausted.
     * 
     * @param  object  $queue  \prggmr\Queue
     * 
     * @return  boolean
     */
    public function queue_exhausted($queue)
    {
        $queue->reset();
        while($queue->valid()) {
            // if a non exhausted queue is found return false
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
    public function handle_remove($handle, $signal)
    {
        $queue = $this->signal_queue($signal);
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
        $this->_event_history = [];
        $this->set_state(STATE_DECLARED);
    }

    /**
     * Creates a new signal handler.
     *
     * @param  object  $callable  Closure
     * @param  string|int|object  $signal  Signal to attach the handle.
     * @param  integer $priority  Handle priority.
     * @param  integer  $exhaust  Handle exhaustion.
     *
     * @return  object|boolean  Handle, boolean if error
     */
    public function handle($callable, $signal, $priority = QUEUE_DEFAULT_PRIORITY, $exhaust = 1)
    {
        /**
         * Allow for giving the signal first
         */
        if ($signal instanceof Closure) {
            $tmp = $callable;
            $callable = $signal;
            $signal = $tmp;
            unset($tmp);
        }

        if (is_int($signal) && $signal >= 0xE001 && $signal <= 0xE02A) {
            $this->signal(engine_signals::RESTRICTED_SIGNAL, array(
                func_get_args()
            ));
        }

        if (!$callable instanceof Handle) {
            if (!is_callable($callable)) {
                $this->signal(engine_signals::INVALID_HANDLE, array(
                    func_get_args()
                ));
                return false;
            }
            $handle = new Handle($callable, $exhaust);
        }

        $queue = $this->signal_queue($signal);
        if (false !== $queue) {
            $queue->enqueue($handle, $priority);
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
     * @param  boolean  $create  Create the queue if not found.
     * @param  integer  $type  [QUEUE_MIN_HEAP,QUEUE_MAX_HEAP]
     *
     * @return  boolean|object  false|prggmr\Queue
     */
    public function signal_queue($signal, $create = true, $type = QUEUE_MIN_HEAP)
    {
        $complex = false;
        $queue = false;
        if ($signal instanceof \prggmr\Signal\Standard) {
            if ($signal instanceof \prggmr\signal\Complex) {
                $complex = true;
            }
        } else {
            try {
                $signal = new Signal($signal);
            } catch (\InvalidArgumentException $e) {
                $this->signal(engine_signals::INVALID_SIGNAL, array($exception, $signal));
                return false;
            }
        }

        if ($complex) {
            $search = $this->_search_complex($signal);
            if (null !== $search) {
                $queue = $search;
            }
        } else {
            $search = $this->_search($signal);
            if (null !== $search) {
                $queue = $search;
            }
        }

        if (!$queue) {
            if (!$create) {
                return false;
            }
            $queue = new Queue($type);
            if (!$complex) {
                $this->_storage[self::HASH_STORAGE][(string) $signal->info()] = [
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
     * Registers a new sig handle loader which recursively loads files in the
     * given directory when a signal is triggered.
     * 
     * @param  integer|string|object  $signal  Signal to register with
     * @param  string  $directory  Directory to load handles from
     * 
     * @return  object|boolean  \prggmr\Handle|False on error
     */
    public function handle_loader($signal, $directory, $heap = QUEUE_MIN_HEAP)
    {
        if (!is_dir($directory) || !is_readable($directory)) {
            $this->signal(engine_signals::INVALID_HANDLE_DIRECTORY, array(
                $directory, $signal
            ));
        }
        if (!is_string($signal) && !is_int($signal)) {
            $this->signal(engine_signals::INVALID_SIGNAL, array($signal));
            return false;
        }
        // ensure handle always has the highest priority
        $priority = 0;
        if ($heap === QUEUE_MAX_HEAP) {
            $priority = PHP_INT_MAX;
        }
        $engine = $this;
        $handle = $this->handle(function() use ($directory, $engine) {
            $dir = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directory)
                ), '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH
            );
            foreach ($dir as $_file) {
                array_map(function($i){
                    require_once $i;
                }, $_file);
            }
            // Rengine_signalsnal this signal
            // The current event is not passed so the handles will get a clean
            // event.
            // Event analysis will show the handles were loaded from here.
            $engine->signal($this->get_signal(), func_get_args());
            return true;
        }, $signal, 0, 1);
        return $handle;
    }

    /**
     * Searches for a string or integer signal queue in storage.
     * 
     * @param  string|int|object  $signal  Signal for queue
     * 
     * @return  null|object  null|Queue object
     */
    protected function _search($signal) 
    {
        if ($signal instanceof \prggmr\signal\Complex) {
            return null;
        }
        if ($signal instanceof \prggmr\Signal) {
            $signal = $signal->info();
        }
        $signal = (string) $signal;
        if (isset($this->_storage[self::HASH_STORAGE][$signal])) {
            return $this->_storage[self::HASH_STORAGE][$signal][1];
        }
        return null;
    }

    /**
     * Searches for a complex signal. If given a complex signal object
     * it will attempt to locate the signal, otherwise it will evaluate the
     * signals.
     * 
     * @param  string|int|object  $signal  Signal(s) to lookup.
     * 
     * @return  null|array|object
     */
    public function _search_complex($signal)
    {
        if (count($this->_storage[self::COMPLEX_STORAGE]) == 0) {
            return null;
        }
        $locate = false;
        $found = array();
        if (is_string($signal) || is_int($signal)) {
            $locate = true;
        } elseif (!$signal instanceof \prggmr\signal\Complex) {
            $this->signal(engine_signals::INVALID_SIGNAL, array($signal));
            return null;
        }
        if (!$locate) {
            $id = spl_object_hash($signal);
            if (isset($this->_storage[self::COMPLEX_STORAGE][$id])) {
                return $this->_storage[self::COMPLEX_STORAGE][$id][1];
            }
        } else {
            foreach ($this->_storage[self::COMPLEX_STORAGE] as $_key => $_node) {
                $eval = $_node[0]->evaluate($signal);
                if (false !== $eval) {
                    $found[] = [$_node[1], $eval];
                }
            }
        }
        if ($locate && count($found) !== 0) {
            return $found;
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
                $this->signal(engine_signals::INVALID_EVENT, array($event));
            }
            $event = new Event($ttl);
        } else {
            if ($event->get_state() !== STATE_DECLARED) {
                $event->set_state(STATE_RECYCLED);
            }
        }
        $event->set_signal($signal);
        // are we keeping the history
        if (!$this->_store_history) {
            return $event;
        }
        // event history management
        if (null !== $this->_current_event) {
            $this->_event_children[] = $this->_current_event;
            $event->set_parent($this->_current_event);
        }
        $this->_current_event = $event;
        $this->_event_history[] = [$event, $signal, milliseconds()];
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
        if (!$this->_store_history) {
            return null;
        }
        if (count($this->_event_children) !== 0) {
            $this->_current_event = array_pop($this->_event_children);
        } else {
            $this->_current_event = null;
        }
    }

    /**
     * Signals an event.
     *
     * @param  mixed  $signal  Signal instance or signal.
     *
     * @param  array  $vars  Array of variables to pass handles.
     *
     * @param  object  $event  \prggmr\Event
     *
     * @return  object  Event
     */
    public function signal($signal, $vars = null, $event = null, $ttl = null)
    {
        // check variables
        if (null !== $vars) {
            if (!is_array($vars)) {
                $vars = array($vars);
            }
        }

        // load engine event
        $event = $this->_event($signal, $event, $ttl);

        // locate sig handlers
        $queue = new Queue();
        $simple = $this->_search($signal);
        if (null !== $search) {
            $queue->merge($simple->storage());
        }
        $complex = $this->_search_complex($signal);
        var_dump($complex);
        var_dump($signal);
        if (null !== $complex) {
            if (is_array($complex)) {
                array_walk($complex, function($node) use ($queue) {
                    if (is_bool($node[1]) === false) {
                        $data = $node[1];
                        $node[0]->walk(function($handle) use ($data){
                            $handle[0]->params($data);
                        });
                    }
                    $queue->merge($node[0]->storage());
                });
            }
        }

        var_dump($queue);

        // no sig handlers found
        if ($queue->count() === 0) {
            $this->_event_exit($event);
            return $event;
        }

        return $this->_execute($signal, $queue, $event, $vars);
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
     * @param  array  $vars  Array of variables to pass handles.
     * @param  boolean  $interupt  Run the interrupt functions.
     *
     * @return  object  Event
     */
    protected function _execute($signal, $queue, $event, $vars, $interrupt = true)
    {
        if ($event->has_expired()) {
            $this->signal(engine_signals::EVENT_EXPIRED, [$event]);
            return $event;
        }
        // handle pre interupt functions
        if ($interrupt) {
            $this->_interrupt($signal, self::INTERRUPT_PRE, $vars, $event);
            if ($event->get_state() === STATE_HALTED) {
                $this->_event_exit($event);
                return $event;
            }
        }
        // execute sig handlers
        $queue->sort(true);
        $queue->reset();
        while($queue->valid()) {
            if ($event->get_state() === STATE_HALTED) {
                break;
            }
            $handle = $queue->current()[0];
            $handle->set_state(STATE_RUNNING);
            // bind event to allow use of "this"
            $handle->bind($event);
            // set event as running
            $event->set_state(STATE_RUNNING);
            if ($this->_engine_exceptions) {
                $result = $handle($vars);
            } else {
                try {
                    $result = $handle($vars);
                } catch (\Exception $exception) {
                    $event->set_state(STATE_ERROR);
                    $handle->set_state(STATE_ERROR);
                    if ($exception instanceof EngineException) {
                        throw $exception;
                    }
                    $this->signal(engine_signals::HANDLE_EXCEPTION, array(
                        $exception, $signal
                    ));
                }
            }
            if (null !== $result) {
                $event->set_result($result);
                if (false === $result) {
                    $event->halt();
                }
            }
            $handle->set_state(STATE_EXITED);
            $queue->next();
        }
        // handle interupt functions
        if ($interrupt) {
            $this->_interrupt($signal, self::INTERRUPT_POST, $vars, $event);
        }
        $this->_event_exit($event);
        return $event;
    }

    /**
     * Retrieves the event history.
     * 
     * @return  array
     */
    public function event_history(/* ... */)
    {
        return $this->_event_history;
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
     * Generates output for analyzing the system event architecture.
     * 
     * @param  string  $output  File to output analysis, null to return.
     * @param  string  $template  Template to use for generation
     * 
     * @return  void
     */
    public function event_analysis($output, $template = null)
    {
        if (!$this->_store_history) return false;
        if (null === $template) {
            $template = 'html';
        }
        $path = dirname(realpath(__FILE__));
        $template_file = sprintf(
            '%s/%s/%s.php',
            $path, 'templates', $template
        );
        if (!file_exists($template_file)) {
            throw new \InvalidArgumentException(sprintf(
                "Event analysis file %s does not exist",
                $template_file
            ));
        }
        ob_start();
        include $template_file;
        $output = ob_get_contents();
        ob_end_clean();
        file_put_contents($output);
    }

    /**
     * Loads a complex signal library.
     * 
     * @param  string  $name  Signal library name.
     * @param  string|null  $dir  Location of the library. 
     * 
     * @return  void
     */
    public function load_signal($name, $dir = null) 
    {
        // already loaded
        if (isset($this->_libraries[$name])) return true;
        if ($dir === null) {
            $dir = dirname(realpath(__FILE__)).'/signal';
        } else {
            if (!is_dir($dir)) {
                $this->signal(engine_signals::INVALID_SIGNAL_DIRECTORY, $dir);
            }
        }

        if (is_dir($dir.'/'.$name)) {
            $path = $dir.'/'.$name;
            if (file_exists($path.'/__autoload.php')) {
                // keep history of what has been loaded
                $this->_libraries[$name] = true;
                require_once $path.'/__autoload.php';
            } else {
                $this->signal(engine_signals::SIGNAL_LOAD_FAILURE, [$name, $dir]);
            }
        }
    }

    /**
     * Registers a function to interrupt the signal stack before or after a 
     * signal fires.
     * 
     * @param  object  $handle  Handle to execute
     * @param  string|object  $signal
     * @param  int|null  $place  Interuption location. INTERUPT_PRE|INTERUPT_POST
     * @param  int|null  $priority  Interupt priority
     * @param  boolean  $complex  Register the given complex signal as a complex interrupt signal
     * 
     * @return  boolean  True|False false is failure
     */
    public function signal_interrupt($handle, $signal, $interrupt = null, $priority = null, $complex = false) 
    {
        // Variable Checks
        if (!$handle instanceof Handle) {
            if (!$handle instanceof \Closure) {
                $this->signal(engine_signals::INVALID_HANDLE, $handle);
                return false;
            } else {
                $handle = new Handle($handle);
            }
        }
        if (!is_object($signal) && !is_int($signal) && !is_string($signal)) {
            $this->signal(engine_signals::INVALID_SIGNAL, $signal);
            return false;
        }
        if (null === $interrupt) {
            $interrupt = self::INTERRUPT_PRE;
        }
        if (!is_int($interrupt) || $interrupt >= 3) {
            $this->signal(engine_signals::INVALID_INTERRUPT, $interrupt);
        }
        if (!isset($this->_storage[self::INTERRUPT_STORAGE][$interrupt])) {
            $this->_storage[self::INTERRUPT_STORAGE][$interrupt] = [[], []];
        }
        $storage =& $this->_storage[self::INTERRUPT_STORAGE][$interrupt];
        if ($signal instanceof signal\Complex && $complex) {
            $storage[self::COMPLEX_STORAGE][] =  [
                $signal, $handle, $priority
            ];
        } else {
            $name = (is_object($signal)) ? get_class($signal) : $signal;
            if (!isset($storage[self::HASH_STORAGE][$name])) {
                $storage[self::HASH_STORAGE][$name] = [];
            }
            $storage[self::HASH_STORAGE][$name][] = [
                $signal, $handle, $priority
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
    private function _interrupt($signal, $type, $vars, &$event)
    {
        // do nothing no interupt registered
        if (!isset($this->_storage[self::INTERRUPT_STORAGE][$type])) {
            return true;
        }
        $name = (is_object($signal)) ? get_class($signal) : $signal;
        $queue = null;
        if (count($this->_storage[self::INTERRUPT_STORAGE][$type][self::COMPLEX_STORAGE]) != 0) {
            foreach ($this->_storage[self::INTERRUPT_STORAGE][$type][self::COMPLEX_STORAGE] as $_node) {
                $eval = $_node[0]->evalute($signal);
                if (false !== $eval) {
                    if (true !== $eval) {
                        $_node[1]->params($eval);
                    }
                    if (null === $queue) {
                        $queue = new Queue();
                    }
                    $queue->enqueue($_node[1], $_node[2]);
                }
            }
        }
        if (isset($this->_storage[self::INTERRUPT_STORAGE][$type][self::HASH_STORAGE][$name])) {
            foreach ($this->_storage[self::INTERRUPT_STORAGE][$type][self::HASH_STORAGE][$name] as $_node) {
                if ($name === $_node[0] || $signal === $_node[0]) {
                    if (null === $queue) {
                        $queue = new Queue();
                    }
                    $queue->enqueue($_node[1], $_node[2]);
                }
            }
        }
        if (null !== $queue) {
            $this->_execute($signal, $queue, $event, $vars, false);
        }
    }

    /**
     * Cleans any exhausted signal queues from the engine.
     * 
     * @param  boolean  $history  Erase any history of the signal the signals cleaned.
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
                                $_node[0] : $_node[0]->info()
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
        if ($signal instanceof signal\Complex) {
            $search = $this->_search_complex($signal);
            if ($search[0] !== self::SEARCH_FOUND) return false;
            unset($this->_storage[self::COMPLEX_STORAGE][$search[3]]);
        } elseif (isset($this->_storage[self::HASH_STORAGE][$signal])) {
            unset($this->_storage[self::HASH_STORAGE][$signal]);
        } else {
            return false;
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
        if (!$this->_store_history || count($this->_event_history) == 0) {
            return false;
        }
        // recursivly check if any events are a child of the given signal
        // because if the chicken doesn't exist neither does the egg ...
        // or does it?
        $descend_destory = function($event) use ($signal, &$descend_destory) {
            // child and not a child of itself
            if ($event->is_child() && $event->get_parent() !== $event) {
                return $descend_destory($event->get_parent());
            }
            if ($event->get_signal() === $signal) {
                return true;
            }
        };
        foreach ($this->_event_history as $_key => $_node) {
            if ($_node[1] === $signal) {
                unset($this->_event_history[$_key]);
            } elseif ($_node[0]->is_child() && $_node[0]->get_parent() !== $_node[0]) {
                if ($descend_destory($_node[0]->get_parent())) {
                    unset($this->_event_history[$_key]);
                }
            }
        }
    }

    /**
     * Sets the flag for storing the event history.
     * If disabling the history this does not clear the current.
     *
     * @param  boolean  $flag
     *
     * @return  void
     */
    public function save_event_history($flag)
    {
        $this->_store_history = (bool) $flag;
    }
}

class EngineException extends \Exception {

    protected $_type = null;

    protected $_args = null;

    /**
     * Constructs a new engine exception.
     * 
     * @param  string|null  $message  Exception message if given
     * @param  integer  $type  Engine error type
     * @param  array  $args  Arguments present for exception
     */
    public function __construct($message, $type, $args)
    {
        parent::__construct($message);
        $this->_type = $type;
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
    public function get_engine_code(/* ... */)
    {
        return $this->_type;
    }
}