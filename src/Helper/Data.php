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

use ArgumentCountError;
use ArrayAccess;
use ArrayObject;
use Closure;
use Countable;
use Mainframe\Utils\Data\Pair;
use Mainframe\Utils\Data\StackableInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Exception\OutOfBoundsException;
use Mainframe\Utils\Exception\OutOfRangeException;
use SplDoublyLinkedList;
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
     * @param mixed $items Anything that can be converted to an array (see to_array)
     * @param string|int|null $path The key or dot notation path to the value to get
     * @param mixed|null $default The value to return if no value found at key/path
     * @param string|null $delim The character to use as a path delimiter (defaults to a dot or period)
     * @return mixed
     * @throws OutOfBoundsException If key/path not found and no default provided
     */
    public static function get($items, $path = null, $default = null, $delim = null)
    {
        if (is_null($delim)) {
            $delim = '.';
        }

        if (!is_array($items)) {
            $items = static::toArray($items, true);
        }

        if (array_key_exists($path, $items)) {
            return $items[$path];
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
                $items
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

    /**
     * @param $items
     * @return mixed
     */
    public static function random($items)
    {
        return static::getByPos($items, rand(1, count($items)));
    }

    /**
     * @param $items
     * @return array|mixed
     */
    public static function reverse($items)
    {
        if (method_exists($items, 'reverse')) {
            return $items->reverse();
        }
        $rev = array_reverse(static::toArray($items));
        if (
            $items instanceof SplDoublyLinkedList ||
            $items instanceof StackableInterface
        ) {
            $class = get_class($items);
            $list = new $class($rev);
            Data::append($list, $rev);
            return $list;
        }
        return $rev;
    }

    /**
     * @param $items
     * @param callable|null $func
     * @param bool $expected
     * @return bool
     */
    public static function assert($items, ?callable $func = null, $expected = true): bool
    {
        $index = 0;
        foreach (static::toArray($items) as $key => $val) {
            // @tests needed
            if (is_null($func)) {
                if ($val != $expected) {
                    return false;
                }
            } elseif ($func($val, $key, $index++) !== $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the nth item in the data
     *
     * @param iterable $items The data to positionally retrieve an item from
     * @param int $pos The position (from 1)
     * @return mixed
     */
    public static function getByPos($items, int $pos, $default = null)
    {
        return OutofRangeException::recover(
            function () use ($items, $pos) {
                return Data::get($items, static::getKeyByPos($items, $pos));
            },
            $default
        );
    }

    /**
     * Get the nth item in the data
     *
     * @param iterable $items The data to positionally retrieve an item from
     * @param int $pos The position (from 1)
     * @return mixed
     */
    public static function getKeyByPos($items, int $pos)
    {
        if (!is_array($items)) {
            $items = static::toArray($items, true);
        }

        $total = count($items);
        if ($pos < 0) {
            $pos = $total - abs($pos) + 1;
        }

        if ($pos >= 0) {
            $cur = 1;
            foreach ($items as $key => $val) {
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
     * @param mixed $items Anything that can be converted to an array (see to_array)
     * @param string|int|null $path The key or dot notation path to the value to get
     * @param string|null $delim The character to use as a path delimiter (defaults to a dot or period)
     * @return bool
     */
    public static function has($items, $path = null, $delim = null): bool
    {
        return (static::get($items, $path, static::NOT_FOUND, $delim) !== static::NOT_FOUND);
    }

    /**
     * Set a value at a given key or dot-notation path
     *
     * @param array|ArrayAccess $items The array/object you'd like to set a value in
     * @param string|int $path Either the key or a dot-notation path to the element you want to set
     * @param mixed $value The value to set it to
     * @param string $delim The dot notation path delimiter (defaults to a dot)
     */
    public static function set(&$items, $path, $value, $delim = null): void
    {
        if (is_null($delim)) {
            $delim = static::DELIM;
        }

        InvalidArgumentException::raiseUnless(
            is_array($items) || $items instanceof ArrayAccess,
            'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
            [__METHOD__, typeof($items)]
        );

        $arr = &$items;
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

    /**
     * @param $items
     * @param $path
     * @param mixed $delim
     */
    public static function delete(&$items, $path, $delim = null): void
    {
        if (is_null($delim)) {
            $delim = static::DELIM;
        }

        InvalidArgumentException::raiseUnless(
            is_array($items) || $items instanceof ArrayAccess,
            'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
            [__METHOD__, typeof($items)]
        );

        $arr = &$items;
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

    /**
     * @param $items
     * @return void
     */
    public static function clear(&$items): void
    {
        if (is_array($items)) {
            $items = [];
            return;
        }

        if (is_object($items)) {
            if (method_exists($items, 'clear')) {
                $items->clear();
                return;
            }
            if ($items instanceof ArrayAccess) {
                foreach ($items as $key => $val) {
                    unset($items[$key]);
                }
                return;
            }
        }
    }

    /**
     * @param iterable|Countable $items
     * @return int
     */
    public static function count($items): int
    {
        if (is_object($items)) {
            if (method_exists($items, 'count')) {
                return $items->count();
            }
        }
        if (is_countable($items)) {
            return count($items);
        }
        InvalidArgumentException::raise(
            'Cannot produce a count for value of type: %s',
            [typeof($items)]
        );
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
    public static function contains($items, $value, $key = null): bool
    {
//        $items = static::toArray($items);
//        $i = 0;
//        foreach ($items as $k => $v) {
//            $matchkey = is_null($key) || $key === $k;
//            if (is_callable($value)) {
//                if (value_of($value, $v, $k, $i)) {
//                    return $matchkey;
//                }
//            } else {
//                if ($value === $v) {
//                    return $matchkey;
//                }
//            }
//            $i++;
//        }
//        return false;

        return (bool) static::reduce($items, function ($a, $v, $k, $i) use ($key, $value) {
            $matchkey = fn ($m) => is_null($key) || $m === $key;
            if (is_callable($value)) {
                if ($value($v, $k, $i)) {
                    return $matchkey($k);
                }
            } else {
                if ($value === $v) {
                    return $matchkey($k);
                }
            }
        });

    }

    /**
     * Assert that a list of values contains ALL of another list of values
     *
     * @param iterable $items The array or iterable
     * @param iterable $values Values to check
     * @param bool $matchKey Whether to also match the key
     * @return bool
     */
    public static function containsAll($items, iterable $values, bool $matchKey = false): bool
    {
        return static::assert($values, fn ($v, $k, $i) => static::contains($items, $v, $matchKey ? $k : null));
    }

    /**
     * Assert that a list of values contains ANY of another list of values
     *
     * @param iterable $items The array or iterable
     * @param iterable $values Values to check
     * @param bool $matchKey Whether to also match the key
     * @return bool
     */
    public static function containsAny($items, iterable $values, bool $matchKey = false): bool
    {
        return static::any($values, fn ($v, $k, $i) => static::contains($items, $v, $matchKey ? $k : null));
    }

    /**
     * Loop through an iterable full of either callbacks or values and, if they are callbacks,
     * invoke them and return their value, passing any additional args to them that were passed
     * to this method. If not a callable, just keep the value as-is. Finally, return the result
     *
     * This method is useful in situations where you have a bunch of callbacks that need to be invoked
     *
     * @param callable|mixed $items An iterable containing callbacks and/or values
     * @param mixed ...$args Any additional args that should be passed to the callbacks
     * @return array
     */
    public static function resolve($items, ...$args): array
    {
        $resolved = [];
        $items = static::toArray($items);
        foreach ($items as $key => $val) {
            $resolved[$key] = value_of($val, ...$args);
        }
        return $resolved;
    }

    /**
     * @param $items
     * @param bool $force
     * @return array
     */
    public static function toArray($items, $force = false): array
    {
        if (is_array($items)) {
            return $items;
        }

        // if items is an object...
        if (is_object($items)) {
            if ($items instanceof ArrayObject) {
                return $items->getArrayCopy();
            }
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
     * @param $items
     * @return Closure
     */
    public static function toGenerator($items): Closure
    {
        // $items = static::toArray($items);1222
        return function () use ($items) {
            foreach ($items as $key => $val) {
                yield $key => $val;
            }
        };
    }

    /**
     * @param $items
     * @return bool
     */
    public static function isEmpty($items): bool
    {
        if (is_object($items)) {
            if (method_exists($items, 'isEmpty')) {
                return $items->isEmpty();
            }
            if (method_exists($items, 'count')) {
                return $items->count() === 0;
            }
        }
        return empty($items);
    }

    /**
     * @param $items
     * @param callable|null $func
     * @param null $default
     * @return mixed
     */
    public static function first($items, ?callable $func, $default = null)
    {
        InvalidArgumentException::raiseUnless(
            is_iterable($items),
            'Cannot get first item from data structure, as it is not iterable'
        );

        $index = 0;
        foreach ($items as $key => $val) {
            if (is_null($func) || $func($val, $key, $index++)) {
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

    /**
     * @param $items
     * @param callable|null $func
     * @param null $default
     * @return mixed
     */
    public static function last($items, ?callable $func, $default = null)
    {
        InvalidArgumentException::raiseUnless(
            is_iterable($items),
            'Cannot get last item from data structure, as it is not iterable'
        );

        if ($items instanceof \SplDoublyLinkedList) {
            // set it to iterate from the other end
            $items = static::reverse($items);
        } else {
            if (method_exists($items, 'reverse')) {
                $items = $items->reverse();
            } else {
                $items = array_reverse(static::toArray($items));
            }
        }

        $args = [$items, $func];
        if ($def = func_get_arg(2)) {
            // we only want to send a default if one was provided explicitly even if it was null
            $args[] = $def;
        }
        return static::first(...$args);
    }

    /**
     * Get keys from array or iterable
     *
     * @param ?iterable $items The data to get keys from
     * @return array
     */
    public static function keys($items)
    {
        return array_keys(Data::toArray($items));
    }

    /**
     * Get keys from array or iterable
     *
     * @param ?iterable $items The data to get keys from------------------------==-
     * @return array
     */
    public static function values($items)
    {
        return Data::toIndex($items);
    }

    /**
     * Push a single item onto the data structure (optionally at given index)
     *
     * @param $items
     * @param $value
     * @param null $index
     */
    public static function push(&$items, $value, $index = null)
    {
        if (is_null($index)) {
            if (is_array($items) || $items instanceof ArrayAccess) {
                $items[] = $value;
                return;
            }
            if (is_object($items)) {
                if (method_exists($items, 'push')) {
                    return $items->push($value);
                }
            }
        } else {
            if (is_array($items) || $items instanceof ArrayAccess) {
                $items[$index] = $value;
                return;
            }
            if (is_object($items)) {
                if ($items instanceof SplDoublyLinkedList) {
                    $items->add($index, $value);
                    return;
                }
            }
        }
        BadMethodCallException::raise('Unable to push item onto data structure of type "%s"', [typeof($items)]);
    }

    /**
     * Append multiple items to the data structure
     *
     * @param $items
     * @param iterable $values
     */
    public static function append(&$items, iterable $values): void
    {
        foreach ($values as $val) {
            Data::push($items, $val);
        }
    }

    /** @todo these are a bit of a mess -- need to clean them up (invoke, invokeall etc) */

    /**
     * @param $items
     * @param $key
     * @param $func
     * @param mixed $default
     * @return mixed
     */
    public static function apply($items, $key, $func, $default = null)
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

    /**
     * @param $items
     * @param $func
     * @param null $default
     * @return array
     */
    public static function applyAll($items, $func, $default = null): array
    {
        $applied = [];
        foreach (Data::toArray($items) as $key => $val) {
            $applied[$key] = static::apply($items, $key, $func);
        }
        return $applied;
    }

    /**
     * @param $items
     * @param callable|null $func
     * @return array
     */
    public static function filter($items, ?callable $func = null): array
    {
        return static::partition($items, $func)[0];
    }

    /**
     * @param $items
     * @param callable $func
     * @return array
     */
    public static function exclude($items, callable $func): array
    {
        return static::partition($items, $func)[1];
    }

    /**
     * @param $items
     * @param callable $func
     * @return array
     */
    public static function map($items, callable $func): array
    {
        $mapped = [];

        $index = 0;
        foreach (Data::toArray($items) as $key => $val) {
            $val = $func($val, $key, $index++);
            if ($val instanceof Pair) {
                [$key, $val] = $val->toArray();
            }
            $mapped[$key] = $val;
        }

        return $mapped;
    }

    /**
     * @param $items
     * @param callable $func
     */
    public static function each($items, callable $func): void
    {
        $i = 0;
        foreach (Data::toArray($items) as $key => $val) {
            if (false === $func($val, $key, $i++)) {
                return;
            }
        }
    }

    /**
     * @param $items
     * @param callable $func
     * @param mixed ...$extras
     */
    public static function invokeAll($items, callable $func, ...$extras): void
    {
        $i = 0;
        foreach (Data::toArray($items) as $key => $val) {
            if (false === $func($val, ...$extras)) {
                return;
            }
        }
    }

    /**
     * Expect than any value in the collection is equal to another value
     * This method does exactly what "assert" does except that it will return true if ANY item asserts true.
     *
     * @param callable|null $func Expect callback
     * @param bool $expected Expected value from callback
     *
     * @return bool
     */
    public static function any($items, ?callable $func = null, $expected = true): bool
    {
        $index = 0;
        foreach (Data::toArray($items) as $key => $val) {
            try {
                if ($expected == $func($val, $key, $index++)) {
                    return true;
                }
            } catch (ArgumentCountError $error) {
                if ($expected == $func($val)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $items
     * @param callable $func
     * @return mixed
     */
    public static function pipe($items, callable $func)
    {
        return $func($items);
    }

    /**
     * @param $items
     * @param callable|null $func
     * @return array|array[]
     */
    public static function partition($items, ?callable $func = null): array
    {
        $truthy = [];
        $falsey = [];
        $index = 0;
        if (is_null($func)) {
            $func = 'truthy';
        }
        foreach (static::toArray($items) as $key => $value) {
            if ($func($value, $key, $index++)) {
                $truthy[$key] = $value;
            } else {
                $falsey[$key] = $value;
            }
        }

        return [
            $truthy,
            $falsey
        ];
    }

    /**
     * @param $items
     * @param callable $func
     * @param null $initial
     * @return mixed|null
     */
    public static function reduce($items, callable $func, $initial = null)
    {
        $index = 0;
        $reduced = $initial;
        foreach (static::toArray($items) as $key => $val) {
            $reduced = $func($reduced, $val, $key, $index++);
        }

        return $reduced;
    }

    /**
     * @param $items
     * @param int $offset
     * @param int|null $length
     * @return array
     */
    public static function slice($items, int $offset, ?int $length = null): array
    {
        [$start, $end] = absolute_offset_length(Data::count($items), $offset, $length);
        return static::filter($items, function($v, $k, $o) use ($start, $end) {
            return ($o >= $start && $o <= $end);
        });

    }

    /**
     * @param $items
     * @param $input
     * @param int $offset
     * @param int|null $length
     * @return array
     */
    public static function splice($items, $input, int $offset, ?int $length = null): array
    {
        [$start, $end] = absolute_offset_length($items, $offset, $length);
        $excluded = static::filter($items, function($v, $k, $o) use ($start, $end) {
            return ($o < $start && $o > $end);
        });
        [$top, $bottom] = Data::cut($items, $offset);
        [$_, $bottom] = Data::cut($bottom, $length);
        return Data::union($top, $input, $bottom);
    }

    /**
     * Combine multiple iterables by "stacking" them. This method does not preserve keys
     *
     * @param mixed ...$pieces
     * @return array
     */
    public static function union(...$pieces): array
    {
        $result = [];
        foreach ($pieces as $piece) {
            Data::append($result, $piece);
        }
        return $result;
    }

    /**
     * Combine multiple iterables by "merging" them. This method preserves keys and when duplicate keys
     * are found, previous ones are simply overwritten.
     *
     * @param mixed ...$pieces
     * @return array
     */
    public static function merge(...$pieces): array
    {
        $result = [];
        foreach ($pieces as $piece) {
            foreach ($piece as $key => $val) {
                Data::set($result, $key, $val);
            }
        }
        return $result;
    }

    /**
     * Cut an iterable at the given offset and insert the given items
     *
     * @param iterable $items
     * @param int $offset
     * @param $input
     * @return array
     */
    public static function cutInto(iterable $items, $input, int $offset): array
    {
        [$top, $bottom] = static::cut($items, $offset);
        return Data::union($top, $input, $bottom);
    }

    /**
     * @param $items
     * @param int $offset
     * @return array|array[]
     */
    public static function cut($items, int $offset): array
    {
        $count = count($items);
        if ($offset < (0 - $count)) {
            return [[], Data::toArray($items)];
        }
        if ($offset > $count) {
            return [Data::toArray($items), []];
        }
        [$offset, $_] = absolute_offset_length($items, $offset);
        return static::partition($items, fn($v, $k, $i) => $i < $offset);
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