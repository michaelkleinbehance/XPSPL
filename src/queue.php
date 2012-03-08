<?php
namespace prggmr;
/**
 *  Copyright 2010-12 Nickolas Whiting
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 *
 * @author  Nickolas Whiting  <prggmr@gmail.com>
 * @package  prggmr
 * @copyright  Copyright (c), 2010-12 Nickolas Whiting
 */

use \SplFixedArray,
    \InvalidArgumentException;

/**
 * Defines the maximum number of items allowed within a Queue.
 *
 */
if (!defined('QUEUE_MAX_SIZE')) {
    define('QUEUE_MAX_SIZE', 15);
}

/**
 * As of v0.3.0 Queues no longer maintain a reference to a signal and rather
 * carry only a "data" property which the engine will pass to a signal for
 * handling determination.
 *
 * The Queue is still a representation of a PriorityQueue and will remain so 
 * until the issues with PHP's current implementation are addressed.
 *
 * To offset some of the performance Queue are now a SplFixedArray.
 *
 * The queue is still a MinHeap.
 */
class Queue extends \SplFixedArray {

    /**
     * The data which the queue represents.
     *
     * @var  mixed
     */
    protected $_data = null;

    /**
     * Flag for queue prioritizing.
     * 
     * @var  boolean
     */
    protected $_dirty = false;

    /**
     * Constructs a new queue object.
     *
     * @param  mixed  $data  Data the queue represents
     *
     * @return  void
     */
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * Returns the data the queue represents.
     *
     * @return  object
     */
    public function getRepresentation(/* ... */)
    {
        return $this->_data;
    }

    /**
     * Pushes a new handler into the queue.
     *
     * @param  callable  $callable  Callable variable
     * @param  integer $priority  Priority of the callable
     *
     * @throws  OverflowException  If max size exceeded
     *
     * @return  void
     */
    public function enqueue($callable, $priority = 100)
    {
        $size = $this->getSize();
        if ($size > QUEUE_MAX_SIZE - 1) {
            throw new \OverflowException(
                'Queue max size reached'
            );
        }
        $this->setSize($size + 1);
        $this->_dirty = true;
        if (null === $priority || !is_int($priority)) $priority = 100;
        $node = new \SplFixedArray(2);
        $node->offsetSet(0, $callable);
        $node->offsetSet(1, $priority);
        parent::offsetSet($size, $node);
    }

    /**
    * Removes a handle from the queue.
    *
    * @param  mixed  $callable  Reference to callable
    *
    * @throws  InvalidArgumentException
    * @return  boolean
    */
    public function dequeue($callable)
    {
        $size = $this->getSize();
        while($this->valid()) {
            if ($this->current() === $callable) {
                $this->_dirty = true;
                parent::offsetUnset($this->key());
                // decrease size by 1
                $this->setSize($size - 1);
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the current array node.
     * 
     * @param  boolean  $priority  Return the node containing priority.
     * 
     * @return  callable, array
     */
    public function current($priority = false) 
    {
        $current = parent::current();
        if ($priority) {
            return $current;
        }
        return $current[0];
    }

    /**
     * Rewinds the iterator to prepare for iteration.
     *
     * @param  boolean  $prioritize  Flag to prioritize the queue.
     *
     * @return  void
     */
    public function rewind($prioritize = true)
    {
        if ($prioritize) {
            $this->_prioritize();
        }
        return parent::rewind();
    }

    /**
     * Prioritizes the queue.
     *
     * @return  void
     */
    protected function _prioritize(/* ... */)
    {
        /**
         * I really do not like having to do this ...
         * PHP should really consider allowing for usort to accept an
         * ArrayAccess object.
         */
        // already prioritized?
        if (!$this->_dirty) return null;
        $tmp = array();
        $this->rewind(false);
        while($this->valid()) {
            $node = $this->current(true);
            $priority = $node[1];
            if (!isset($tmp[$priority])) {
                $tmp[$priority] = array();
            }
            $tmp[$priority][] = $node[0];
            $this->next();
        }
        ksort($tmp, SORT_NUMERIC);
        $this->flush();
        foreach ($tmp as $_priority => $_nodes) {
            foreach ($_nodes as $_node) {
                $this->enqueue($_node, $_priority);
            }
        }
        $this->_dirty = false;
    }

    /**
     * Flushes the queue.
     *
     * @return  void
     */
    public function flush(/* ... */)
    {
        $this->setSize(0);
    }

    public function offsetSet($index, $data = null)
    {
        throw new \Exception(
            'offsetSet method disallowed; use of enqueue required'
        );
    }

    public function offsetUnset($object)
    {
        throw new \Exception(
            'offsetUnset method disallowed; use of dequeue required'
        );
    }
}