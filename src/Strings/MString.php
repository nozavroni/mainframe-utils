<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Strings;

use Mainframe\Utils\Helper\Str;
use Symfony\Component\String\UnicodeString;

class MString extends UnicodeString
{
    /**
     * Create a new string object
     *
     * @param mixed|callable $val The value to create a string from
     * @return MString
     */
    public static function create($val): MString
    {
        return new MString((string)value_of($val));
    }

    /**
     * Create a slug from this string
     *
     * @param int|null $maxlen If set, resulting string will be truncated to this length
     * @return MString
     */
    public function sluggify(?int $maxlen = null): MString
    {
        return static::create(Str::sluggify((string)$this, $maxlen));
    }

    /**
     * Is string at least X chars in length?
     *
     * @param int $length The minimum length
     * @param bool $inclusive Should comparison be inclusive?
     * @return bool
     */
    public function isMinLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() >= $length :
            $this->length() > $length;
    }

    /**
     * Is string at most X chars in length?
     *
     * @param int $length The maximum length
     * @param bool $inclusive Should comparison be inclusive?
     * @return bool
     */
    public function isMaxLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() <= $length :
            $this->length() < $length;
    }

    /**
     * Is string exactly X chars in length?
     *
     * @param int $length The length
     * @return bool
     */
    public function isLength(int $length): bool
    {
        return $this->length() == $length;
    }
}