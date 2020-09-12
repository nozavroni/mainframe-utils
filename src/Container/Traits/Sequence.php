<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Container\Traits;

use Mainframe\Utils\Container\Index;
use Mainframe\Utils\Helper\Data;

trait Sequence
{
    abstract public function count();

    /**
     * Get a slice of this index
     * Returns the sliced items in a new index. Indexes are not preserved.
     *
     * @param int $offset The offset to start at
     * @param null $length The length (or if negative, the end position)
     * @return Index
     */
    public function slice($offset, $length = null): Index
    {
        // TODO: Implement slice() method.
    }

    /**
     * Splice an index into this index
     * Returns a new index with the spliced data. Indexes are not preserved.
     *
     * @param int $offset The offset to start at
     * @param null $length The length (or if negative, the end position)
     * @param Index $index The index to splice into this index
     * @return Index
     */
    public function splice($offset, $length, iterable $items): Index
    {
        // TODO: Implement splice() method.
    }

    /**
     * Get the numeric index of a given value
     * Returns the numeric index of the first item it finds that matches the value.
     *
     * @param mixed $value The value to get the index of
     * @return int
     */
    public function indexOf($value): int
    {
        // TODO: Implement indexOf() method.
    }

    /**
     * Get an index of numeric offsets of each item that matches $value
     *
     * @param mixed $value The value to get the indexes of
     * @return Index
     */
    public function indexesOf($value, $strict = false): Index
    {
        $cmp = $strict ?
            fn ($a, $b) => $a === $b :
            fn ($a, $b) => $a == $b;

        $indexes = new Index;
        foreach (Data::toIndex($this) as $i => $val) {
            if ($cmp($val, $value)) {
                $indexes->push($i);
            }
        }
        return $indexes;
    }

    /**
     * Get an index of all keys that match $value
     *
     * @param mixed $value The value to get the indexes of
     * @return Index
     */
    public function keysOf($value, $strict = false): Index
    {
        $cmp = $strict ?
            fn ($a, $b) => $a === $b :
            fn ($a, $b) => $a == $b;

        $keys = new Index();
        foreach (Data::toArray($this) as $key => $val) {
            if ($cmp($val, $value)) {
                $keys->push($key);
            }
        }
        return $keys;
    }

//    /**
//     * Get the first item in the index
//     * If a predicate callback is provided, then return the first item that passes the predicate.
//     * If nothing passes the predicate or the index is empty, return the default. If no default
//     * is provided, throw an OutOfBoundsException.
//     *
//     * @param callable|null $predicate Optional assertion callback
//     * @param mixed|null $default The default to return of predicate fails for all items
//     * @return mixed
//     * @throws OutOfBoundsException
//     */
//    public function first(?callable $predicate = null, $default = null)
//    {
//        return Container::first($this, $predicate, $default);
//    }
//
//    /**
//     * Get the last item in the index
//     * If a predicate callback is provided, then return the last item that passes the predicate.
//     * If nothing passes the predicate or the index is empty, return the default. If no default
//     * is provided, throw an OutOfBoundsException.
//     *
//     * @param callable|null $predicate Optional assertion callback
//     * @param mixed|null $default The default to return of predicate fails for all items
//     * @return mixed
//     * @throws OutOfBoundsException
//     */
//    public function last(?callable $predicate = null, $default = null)
//    {
//        return Container::last($this, $predicate, $default);
//    }

}