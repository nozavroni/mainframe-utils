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

use Mainframe\Utils\Data\IndexInterface;
use Mainframe\Utils\Exception\OutOfBoundsException;
use Mainframe\Utils\Helper\Data;

trait Sequence
{
    protected $storage;

    /**
     * Get a value by offset (from zero)
     *
     * @param int $pos The position (starting from 0 and can be negative to start from the end)
     * @param null $default The value to return if no item at requested position
     * @return mixed
     */
    public function getOffset($pos, $default = null)
    {
        if ($pos < 0) {
            $pos = count($this->storage) + $pos;
        }
        $i = 0;
        $items = Data::toArray($this->storage);
        foreach ($items as $item) {
            if ($pos === $i) {
                return $item;
            }
            $i++;
        }
        return $default;
    }

    /**
     * Get a slice of this index
     * Returns the sliced items in a new index. Indexes are not preserved.
     *
     * @param int $offset The offset to start at
     * @param null $length The length (or if negative, the end position)
     * @return IndexInterface
     */
    public function slice($offset, $length = null): IndexInterface
    {
        // TODO: Implement slice() method.
    }

    /**
     * Splice an index into this index
     * Returns a new index with the spliced data. Indexes are not preserved.
     *
     * @param int $offset The offset to start at
     * @param null $length The length (or if negative, the end position)
     * @param IndexInterface $index The index to splice into this index
     * @return IndexInterface
     */
    public function splice($offset, $length, iterable $items): IndexInterface
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
     * @return IndexInterface
     */
    public function indexes($value): IndexInterface
    {
        // TODO: Implement indexes() method.
    }

    /**
     * Prepend any number of items into this index
     * Implementers should Data::toArray() $items in order to always prepend an iterable even
     * if a scalar was provided.
     *
     * @param iterable|mixed $items
     * @return IndexInterface
     */
    public function prepend($items): IndexInterface
    {
        // TODO: Implement prepend() method.
    }

    /**
     * Append any number of items into this index
     * Implementers should Data::toArray() $items in order to always append an iterable even
     * if a scalar was provided.
     *
     * @param iterable|mixed $items
     * @return IndexInterface
     */
    public function append($items): IndexInterface
    {
        // TODO: Implement append() method.
    }

    /**
     * Get the first item in the index
     * If a predicate callback is provided, then return the first item that passes the predicate.
     * If nothing passes the predicate or the index is empty, return the default. If no default
     * is provided, throw an OutOfBoundsException.
     *
     * @param callable|null $predicate Optional assertion callback
     * @param mixed|null $default The default to return of predicate fails for all items
     * @return mixed
     * @throws OutOfBoundsException
     */
    public function first(?callable $predicate = null, $default = null)
    {
        // TODO: Implement first() method.
    }

    /**
     * Get the last item in the index
     * If a predicate callback is provided, then return the last item that passes the predicate.
     * If nothing passes the predicate or the index is empty, return the default. If no default
     * is provided, throw an OutOfBoundsException.
     *
     * @param callable|null $predicate Optional assertion callback
     * @param mixed|null $default The default to return of predicate fails for all items
     * @return mixed
     * @throws OutOfBoundsException
     */
    public function last(?callable $predicate = null, $default = null)
    {
        // TODO: Implement last() method.
    }

}