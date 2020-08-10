<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Traits;

use Mainframe\Utils\Data\CollectionInterface;

trait HigherOrder
{
    abstract public function getIterator();

    abstract public static function create($items): CollectionInterface;

    /**
     * Assert callback returns $expected value for each item in collection.
     *
     * This method will loop over each item in the collection, passing them to the callback. If the callback doesn't
     * return $expected value for every item in the collection, it will return false.
     *
     * @param callable|null $func Assert callback
     * @param mixed $expected Expected value from callback
     *
     * @return bool
     */
    public function assert(?callable $func = null, $expected = true): bool
    {
        $index = 0;
        foreach ($this->getIterator() as $key => $val) {
            // @todo needs testing
            if ($expected != value_of($func, $val, $key, $index++)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Assert than any value in the collection is equal to another value
     * This method does exactly what "assert" does except that it will return true if ANY item asserts true.
     *
     * @param callable|null $func Assert callback
     * @param bool $expected Expected value from callback
     *
     * @return bool
     */
    public function any(?callable $func = null, $expected = true): bool
    {
        $index = 0;
        foreach ($this->getIterator() as $key => $val) {
            if ($expected == value_of($func, $val, $key, $index++)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does object contain the given item?
     * Similar to the any method, except this is meant more for searching for a given value and optionally
     * checking that it also has a certain key.
     *
     * @param mixed|callable $value The value to check for
     * @param mixed|callable|null $key The key it must have
     * @return mixed
     */
    public function contains($value, $key = null): bool
    {
        $index = 0;
        $key = value_of($key);
        foreach ($this->getIterator() as $k => $v) {
            $matchkey = is_null($key) || $key === $k;
            if (value_of($value, $v, $k, $index++)) {
                return $matchkey;
            }
        }
        return false;
    }

    /**
     * Return a new object containing only items that pass truth test
     *
     * @param callable|null $func The assertion callback
     * @return CollectionInterface
     */
    public function filter(?callable $func = null): CollectionInterface
    {
        return $this->partition($func)[0];
    }

    /**
     * The opposite of filter
     * Returns only items that do NOT pass a truth test
     *
     * @param callable|null $func The assertion callback
     * @return CollectionInterface
     */
    public function exclude(?callable $func = null): CollectionInterface
    {
        return $this->partition($func)[1];
    }

    /**
     * Apply a callback function to each item in the collection passively
     * To stop looping through the items in the collection, return false from the callback.
     *
     * @param callable $func The callback to use on each item in the collection
     *
     * @return self
     */
    public function each(callable $func): self
    {
        $index = 0;
        foreach ($this->getIterator() as $key => $val) {
            if (false === value_of($func, $val, $key, $index++)) {
                break;
            }
        }

        return $this;
    }

    /**
     * Return first item or first item where callback returns true
     *
     * @param callable|null $func A callback to compare items with (optional)
     *
     * @return mixed|null
     */
    public function first(?callable $func = null, $default = null)
    {
        $index = 0;
        foreach ($this->getIterator() as $key => $val) {
            if (value_of($func, $val, $key, $index)) {
                return $val;
            }
        }

        return $default;
    }

    /**
     * Return last item or last item where callback returns true
     *
     * @param callable|null $func A callback to compare items with (optional)
     *
     * @return mixed|null
     */
    public function last(?callable $func = null, $default = null)
    {
        $reversed = array_reverse(to_array($this->getIterator()));
        return static::create($reversed)->first($func, $default);
    }

    /**
     * Reduce collection into a single value (a.k.a. fold)
     *
     * Apply a callback function to each item in the collection, passing the result to the next call until only a single
     * value remains. The arguments provided to this callback are ($carry, $val, $key, $index) where $carry is the
     * result of the previous call (or if the first call it is equal to the $initial param).
     *
     * @param callable $func The callback function used to "reduce" the collection into a single value
     * @param mixed $initial The (optional) initial value to pass to the callback
     *
     * @return mixed
     */
    public function reduce(callable $func, $initial = null)
    {
        $index = 0;
        $reduced = $initial;
        foreach ($this->getIterator() as $key => $val) {
            $reduced = value_of($func, $reduced, $val, $key, $index++);
        }

        return $reduced;
    }

    /**
     * Create a new collection by applying a callback to each item in the collection
     *
     * The callback for this method should accept the standard arguments ($value, $key, $index). It will be called once
     * for every item in the collection and a new collection will be created with the results.
     *
     * @note It is worth noting that keys will be preserved in the resulting collection, so if you do not want this
     *       behavior, simply call values() on the resulting collection and it will be indexed numerically.
     *
     * @param callable $func A callback that is applied to every item in the collection
     *
     * @return CollectionInterface
     */
    public function map(callable $func): CollectionInterface
    {
        $items = [];

        $index = 0;
        foreach ($this->getIterator() as $key => $val) {
            $items[$key] = value_of($func, $val, $key, $index++);
        }

        return static::create($items);
    }

    /**
     * Partition collection into two collections using a callback
     *
     * Iterates over each element in the collection with a callback. Items where callback returns true are placed in one
     * collection and the rest in another. Finally, the two collections are placed in an array and returned for easy use
     * with the list() function. ( `list($a, $b) = $col->partition(function($val, $key, $index) {})` )
     *
     * @param callable|null $func The comparison callback (if null, it will just check if truthy)
     *
     * @return array<CollectionInterface, CollectionInterface>
     */
    public function partition(?callable $func): array
    {
        $truthy = [];
        $falsey = [];
        $index = 0;
        if (is_null($func)) {
            $func = 'truthy';
        }
        foreach ($this->getIterator() as $key => $value) {
            if (value_of($func, $value, $key, $index++)) {
                $truthy[$key] = $value;
            } else {
                $falsey[$key] = $value;
            }
        }

        // @ todo need a factory class
        return [
            static::create($truthy),
            static::create($falsey)
        ];
    }

    /**
     * Pipe collection through a callback
     *
     * Simply passes the collection as an argument to the given callback.
     *
     * @param callable $func The callback function (passed only one arg, the collection itself)
     *
     * @return mixed
     */
    public function pipe(callable $func)
    {
        return value_of($func, $this);
    }

}