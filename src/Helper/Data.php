<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Helper;

use ArrayAccess;
use ArrayObject;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Exception\OutOfBoundsException;
use Mainframe\Utils\Exception\OutOfRangeException;
use function Mainframe\Utils\str;

class Data
{
    const DELIM = '.';

    const NOT_FOUND = '__VALUENOTFOUND__';

    /**`
     * Get a value from a data structure by key or path
     * Using either a key directly, or a key path using dot-notation (index.subindex.etc), this will search the provided
     * data structure and return the value at the given key or path or a default if one is provided. If all else faiels
     * an exception will be thrown.
     *
     * @param mixed $data Anything that can be converted to an array (see to_array)
     * @param string|int|null $path The key or dot notation path to the value to get
     * @param mixed|null $default The value to return if no value found at key/path
     * @param string|null $delim The character to use as a path delimiter (defaults to a dot or period)
     * @return mixed
     * @throws OutOfBoundsException If key/path not found and no default provided
     */
    public static function get($data, $path = null, $default = null, $delim = null)
    {
        if (is_null($delim)) {
            $delim = '.';
        }

        if (!is_array($data)) {
            $data = static::toArray($data, true);
        }

        if (array_key_exists($path, $data)) {
            return $data[$path];
        }

        if (substr_count($path, $delim)) {
            $value = array_reduce(explode($delim, $path),
                function ($arr, $key) {

                    if (is_array($arr)) {
                        if (array_key_exists($key, $arr)) {
                            return $arr[$key];
                        }
                    }
                    return static::NOT_FOUND;

                },
                $data
            );
            if ($value !== static::NOT_FOUND) {
                return $value;
            }
        }

        // unless all the args were passed in, then no default was provided
        OutOfBoundsException::raiseIf(
            func_num_args() < 3,
            'No item found in data for "%s"',
            [$path,]
        );

        return $default;
    }

    public static function random($data)
    {
        return static::getByPos($data, rand(1, count($data)));
    }

    public static function reverse($data)
    {
        if (method_exists($data, 'reverse')) {
            return $data->reverse();
        }
        if (is_array($data) || $data instanceof ArrayObject) {

        }
    }

    public static function assert($data, ?callable $func = null, $expected = true): bool
    {
        $index = 0;
        foreach (static::toArray($data) as $key => $val) {
            // @tests needed
            if (is_null($func)) {
                if ($val != $expected) {
                    return false;
                }
            } elseif (value_of($func, $val, $key, $index++) !== $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the nth item in the data
     *
     * @param iterable $data The data to positionally retrieve an item from
     * @param int $pos The position (from 1)
     */
    public static function getByPos($data, int $pos, $default = null)
    {
        return OutofRangeException::recover(
            function () use ($data, $pos) {
                return Data::get($data, static::getKeyByPos($data, $pos));
            },
            $default
        );
    }

    /**
     * Get the nth item in the data
     *
     * @param iterable $data The data to positionally retrieve an item from
     * @param int $pos The position (from 1)
     */
    public static function getKeyByPos($data, int $pos)
    {
        if (!is_array($data)) {
            $data = static::toArray($data, true);
        }

        $total = count($data);
        if ($pos < 0) {
            $pos = $total - abs($pos) + 1;
        }

        if ($pos >= 0) {
            $cur = 1;
            foreach ($data as $key => $val) {
                if ($cur === $pos) {
                    return $key;
                }
                $cur++;
            }
        }

        OutOfRangeException::raise('No key at position %s', [$pos]);
    }

    /**
     * Check if data structure has a given key or dot-notation path
     *
     * @param mixed $data Anything that can be converted to an array (see to_array)
     * @param string|int|null $path The key or dot notation path to the value to get
     * @param string|null $delim The character to use as a path delimiter (defaults to a dot or period)
     * @return bool
     */
    public static function has($data, $path = null, $delim = null)
    {
        return (static::get($data, $path, static::NOT_FOUND, $delim) !== static::NOT_FOUND);
    }

    /**
     * Set a value at a given key or dot-notation path
     *
     * @param array|ArrayAccess $data The array/object you'd like to set a value in
     * @param string|int $path Either the key or a dot-notation path to the element you want to set
     * @param mixed $value The value to set it to
     * @param string $delim The dot notation path delimiter (defaults to a dot)
     */
    public static function set(&$data, $path, $value, $delim = null)
    {
        if (is_null($delim)) {
            $delim = static::DELIM;
        }

        InvalidArgumentException::raiseUnless(
            is_array($data) || $data instanceof ArrayAccess,
            'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
            [__METHOD__, typeof($data)]
        );

        $arr = &$data;
        $p = str((string)$path);
        if ($p->indexOf($delim)) {
            foreach ($p->split($delim) as $key) {
                $k = (string)$key;
                if (!isset($arr[$k]) || !is_array($arr[$k])) {
                    $arr[$k] = array();
                }
                $arr = &$arr[$k];
            }
            $arr = $value;
        } else {
            $arr[$path] = $value;
        }
    }

    public static function delete(&$data, $path, $delim = null)
    {
        if (is_null($delim)) {
            $delim = static::DELIM;
        }

        InvalidArgumentException::raiseUnless(
            is_array($data) || $data instanceof ArrayAccess,
            'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
            [__METHOD__, typeof($data)]
        );

        $arr = &$data;
        $p = str($path);
        if ($p->indexOf($delim)) {
            $split = $p->split($delim);
            $total = count($split);
            foreach ($split as $key) {
                $k = (string)$key;
                if ($total === 1) {
                    // @refactor this is kinda hacky but it works. if last iteration, delete if exists
                    if (isset($arr[$k])) {
                        unset($arr[$k]);
                    }
                }
                if (isset($arr[$k]) && is_array($arr[$k])) {
                    $arr = &$arr[$k];
                }
                $total--;
            }
        } else {
            unset($arr[$path]);
        }
    }

    public static function clear(&$items)
    {
        if (is_array($items)) {
            $items = [];
        }

        if (is_object($items)) {
            if (method_exists($items, 'clear')) {
                $items->clear();
                return $items;
            }
        }
    }

    /**
     * Determine if data contains given value
     *
     * Checks the data for an item exactly equal to $value. If $value is a callback function, it will be passed
     * the typical arguments ($value, $key, $index) and a true return value will count as a match.
     *
     * If $key argument is provided, key must match it as well. By default key is not required.
     *
     * @param mixed|callable $value The value to check for or a callback function
     * @param mixed $key The key to check for in addition to the value (optional)
     *
     * @return bool
     */
    public static function contains($items, $value, $key = null)
    {
        $items = static::toArray($items);
        $i = 0;
        foreach ($items as $k => $v) {
            $matchkey = is_null($key) || $key === $k;
            if (is_callable($value)) {
                if (value_of($value, $v, $k, $i)) {
                    return $matchkey;
                }
            } else {
                if ($value === $v) {
                    return $matchkey;
                }
            }
            $i++;
        }
    }

    public static function resolve($items): array
    {
        $resolved = [];
        $items = static::toArray($items);
        foreach ($items as $key => $val) {
            $resolved[$key] = value_of($val);
        }
        return $resolved;
    }

    public static function toArray($items, $force = false): array
    {
        if (is_array($items)) {
            return $items;
        }

        // if items is an object...
        if (is_object($items)) {
            // try a few different ways to convert it...
            if (method_exists($items, 'toArray')) {
                return $items->toArray();
            }
            if (is_iterable($items)) {
                return iterator_to_array($items);
            }
            return get_object_vars($items);
        }

        // if not an object
        if (is_null($items)) {
            return [];
        }

        if ($force) {
            return (array)$items;
        }

        // @todo need to test for cases when this is thrown
        InvalidArgumentException::raise(
            '%s was unable to convert value of type "%s" into an array',
            [__METHOD__, typeof($items)]
        );
    }

    /**
     * In this context "index" refers to an array that is sequentially, numerically indexed
     *
     * @param $items
     * @param bool $force
     * @return array
     */
    public static function toIndex($items, $force = false): array
    {
        return array_values(Data::toArray($items, $force));
    }

    /**
     * Converts an iterable to a generator
     *
     * @param iterable|null $items
     * @return \Closure
     */
    public static function toGenerator($items)
    {
        // $items = static::toArray($items);1222
        return function () use ($items) {
            foreach ($items as $key => $val) {
                yield $key => $val;
            }
        };
    }

    public static function isEmpty($data): bool
    {
        if (is_object($data)) {
            if (method_exists($data, 'isEmpty')) {
                return $data->isEmpty();
            }
            if (method_exists($data, 'count')) {
                return $data->count() === 0;
            }
        }
        return empty($data);
    }

    public static function first($data, ?callable $func, $default = null)
    {
        InvalidArgumentException::raiseUnless(
            is_iterable($data),
            'Cannot get first item from data structure, as it is not iterable'
        );

        $index = 0;
        foreach ($data as $key => $val) {
            if (is_null($func) || value_of($func, $val, $key, $index++)) {
                return $val;
            }
        }

        BadMethodCallException::raiseIf(
            func_num_args() < 3,
            "%s failed to find any values that predicate its callback and was not provided a default",
            [__METHOD__]
        );

        return value_of($default);
    }

    public static function last($data, ?callable $func, $default = null)
    {
        InvalidArgumentException::raiseUnless(
            is_iterable($data),
            'Cannot get first item from data structure, as it is not iterable'
        );

        if ($data instanceof \SplDoublyLinkedList) {
            // set it to iterate from the other end
            $data->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO);
        } else {
            if (method_exists($data, 'reverse')) {
                $data = $data->reverse();
            } else {
                $data = array_reverse(static::toArray($data));
            }
        }

        $args = [$data, $func];
        if ($def = func_get_arg(2)) {
            // we only want to send a default if one was provided explicitly even if it was null
            $args[] = $def;
        }
        return static::first(...$args);
    }

    /**
     * Get keys from array or iterable
     *
     * @param ?iterable $data The data to get keys from
     * @return array
     */
    public static function keys($data)
    {
        return array_keys(Data::toArray($data));
    }

    /**
     * Get keys from array or iterable
     *
     * @param ?iterable $data The data to get keys from------------------------==-
     * @return array
     */
    public static function values($data)
    {
        return Data::toIndex($data);
    }

    /**
     * Push a single item onto the data structure (optionally at given index)
     *
     * @param $data
     * @param $value
     * @param null $index
     */
    public static function push(&$data, $value, $index = null)
    {
        if (is_null($index)) {
            if (is_array($data) || $data instanceof ArrayAccess) {
                $data[] = $value;
                return;
            }
            if (is_object($data)) {
                if (method_exists($data, 'push')) {
                    return $data->push($value);
                }
            }
        } else {
            if (is_array($data) || $data instanceof ArrayAccess) {
                $data[$index] = $value;
                return;
            }
            if (is_object($data)) {
                if ($data instanceof \SplDoublyLinkedList) {
                    $data->add($index, $value);
                    return;
                }
            }
        }
        BadMethodCallException::raise('Unable to push item onto data structure of type "%s"', [typeof($data)]);
    }

    /**
     * Append multiple items to the data structure
     *
     * @param $data
     * @param iterable $values
     */
    public static function append(&$data, iterable $values)
    {
        foreach ($values as $val) {
            Data::push($data, $val);
        }
    }

    /** @todo these are a bit of a mess -- need to clean them up (invoke, invokeall etc) */

    public static function apply($data, $key, $func, $default = null)
    {
        if(!is_iterable($func)) {
            $funcs = [$func];
        }
        $args = func_get_args();
        unset($args[2]);
        $value = Data::get(...$args);
        foreach ($funcs as $f) {
            $value = $f($value);
        }
        return $value;
    }

    public static function applyAll($data, $func, $default = null)
    {
        $applied = [];
        foreach (Data::toArray($data) as $key => $val) {
            $applied[$key] = static::apply($data, $key, $func);
        }
        return $applied;
    }

    public static function each($items, callable $func): void
    {
        $i = 0;
        foreach (Data::toArray($items) as $key => $val) {
            if (false === value_of($func, $val, $key, $i++)) {
                return;
            }
        }
    }

    public static function invokeAll($items, callable $func, ...$extras): void
    {
        $i = 0;
        foreach (Data::toArray($items) as $key => $val) {
            if (false === value_of($func, $val, ...$extras)) {
                return;
            }
        }
    }

    /**
     * METHODS for querying data within an associative array or similar data structure
     * by string functions, arithmetic, etc.
     */

//    /**
//     * Creates a string similarity sort function (closure)
//     *
//     * @param $str_cmp string The string to compare against
//     * @param $str_a string The first string to compare
//     * @param $str_b string The second string to compare
//     *
//     * @return Closure
//     */
//    public static function similarity_sorter($str_cmp): Closure
//    {
//        $sorter = function ($a, $b) use ($str_cmp) {
//            $sim_a = Str::similarity($str_cmp, $a);
//            $sim_b = Str::similarity($str_cmp, $b);
//        };
//    }

}