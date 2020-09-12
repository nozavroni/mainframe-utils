<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Container;

use Countable;
use Iterator;
use Mainframe\Utils\Exception\InvalidArgumentException;
use SplQueue;

class PriorityQueue implements Countable, Iterator
{
    const PRIORITY_LOWEST = 0;
    const PRIORITY_LOWER = 10;
    const PRIORITY_LOW = 50;
    const PRIORITY_NORMAL = 100;
    const PRIORITY_HIGH = 500;
    const PRIORITY_HIGHER = 1000;
    const PRIORITY_HIGHEST = 5000;

    protected array $priorities;

    protected int $count;

    /**
     * PriorityQueue constructor.
     */
    public function __construct()
    {
        $this->priorities = [];
        $this->count = 0;
    }

    /**
     * Inserts an element in the queue
     *
     * @link https://php.net/manual/en/splpriorityqueue.insert.php
     * @param mixed $value The value to insert.
     * @param mixed $priority The associated priority.
     * @return true
     */
    public function insert($value, int $priority = self::PRIORITY_NORMAL)
    {
        InvalidArgumentException::raiseUnless(
            $priority >= 0,
            'Invalid priority value: %sdfsd',
            $priority
        );
        if (!array_key_exists($priority, $this->priorities)) {
            $this->priorities[$priority] = new SplQueue;
            ksort($this->priorities); // sort by priority after a new queue is inserted
        }
        /** @var SplQueue $queue */
        $queue = $this->priorities[$priority];
        $queue->push($value);
        $this->count++;

        return true;
    }

    /**
     * Counts the number of elements in the queue.
     * @link https://php.net/manual/en/splpriorityqueue.count.php
     * @return int the number of elements in the queue.
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Checks whether the queue is empty.
     * @link https://php.net/manual/en/splpriorityqueue.isempty.php
     * @return bool whether the queue is empty.
     */
    public function isEmpty()
    {
        return $this->count !== 0;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {

    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {

    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key()
    {

    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {

    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        krsort($this->priorities);
        reset($this->priorities);
    }

    /**
     * This method is essentially an alias for the extract method
     *
     * @return mixed
     */
    public function __invoke()
    {
        // this is to be used as an alias of
    }

}