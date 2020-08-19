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
    public static function create($val): MString
    {
        return new MString((string)value_of($val));
    }

    public function sluggify($maxlen = null): MString
    {
        return static::create(Str::sluggify((string)$this, $maxlen));
    }

    public function isMinLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() >= $length :
            $this->length() > $length;
    }

    public function isMaxLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() <= $length :
            $this->length() < $length;
    }

    public function isLength(int $length): bool
    {
        return $this->length() == $length;
    }
}