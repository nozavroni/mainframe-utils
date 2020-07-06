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

class Num
{
    /**
     * Cast any value that is numeric to an int, all other values will be converted to zero
     *
     * @param mixed $value The value to convert to a number
     * @return int
     */
    public static function to_numeric($value)
    {
        if (is_numeric($value)) {
            return $value + 0;
        }
        return 0;
    }

    /**
     * Returns true for 20.3 as well as for "235.0" and false for 0, 3, "a", "34534"
     *
     * @param mixed $value
     * @return float
     */
    public static function is_float($value)
    {
        return is_numeric($value) && (null !== str($value)->indexOf('.'));
    }

    /**
     * @param mixed $value
     * @return float
     */
    public static function to_float($value)
    {
        if (is_numeric($value)) {
            return $value + 0.0;
        }
        return 0.0;
    }

    // @todo add some methods for formatting using
}