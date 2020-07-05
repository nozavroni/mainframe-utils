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
use Closure;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Exception\OutOfBoundsException;
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
            $data = to_array($data);
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

        if (func_num_args() < 3) {
            // unless all the args were passed in, then no default was provided
            throw new OutOfBoundsException(sprintf('No item found in data for "%s"', $path));
        }

        return $default;
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

        if (!is_array($data) && !($data instanceof ArrayAccess)) {
            throw new InvalidArgumentException(sprintf(
                'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
                __FUNCTION__,
                typeof($data)
            ));
        }

        $arr = &$data;
        $p = str($path);
        if ($p->indexOf($delim)) {
            foreach ($p->split($delim) as $key) {
                $k = (string) $key;
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

        if (!is_array($data) && !($data instanceof ArrayAccess)) {
            throw new InvalidArgumentException(sprintf(
                'Unknown or invalid data structure passed to "%s": expecting an array or iterable, got "%s"',
                __FUNCTION__,
                typeof($data)
            ));
        }

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

    /**
     * Creates a string similarity sort function (closure)
     *
     * @param $str_cmp string The string to compare against
     * @param $str_a string The first string to compare
     * @param $str_b string The second string to compare
     *
     * @return Closure
     */
    public static function similarity_sorter($str_cmp): Closure
    {
        $sorter = function ($a, $b) use ($str_cmp) {
            $sim_a = Str::similarity($str_cmp, $a);
            $sim_b = Str::similarity($str_cmp, $b);
        };
    }

}