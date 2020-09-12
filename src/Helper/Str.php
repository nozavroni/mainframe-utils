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

use Mainframe\Utils\Strings\MString;
use function Symfony\Component\String\u;

class Str
{
    // slug delimiter character
    const SLUG_DELIM = '-';

    /**
     * Fill in a string template with data
     *
     * @param string|array $template Either a template string or an array of them
     * @param array $context This array is used to fill in variables in the template
     * @param string $repl_format This is the format of replacement strings in the template
     * @return string|string[]
     */
    public static function template($template, array $context, string $repl_format = '%s')
    {
        $context = array_map(fn($val) => valinfo($val), $context);
        if (is_array($template)) {
            return array_map(function ($str) use ($context, $repl_format) {
                return static::template($str, $context, $repl_format);
            }, $template);
        }
        // @todo allow for defaults by using preg_replace_callback and a repl_format like this:
        //       "{%s:%s}" or "\b%s<%s>\b"
        return str_replace(
            array_map(
                function ($key) use ($repl_format) {
                    return sprintf($repl_format, $key);
                },
                array_keys($context)
            ),
            array_values($context),
            $template
        );
    }

    /**
     * Just like the template method, but it uses preg_* functions rather than sprintf
     *
     * @param string|array $template Either a template string or an array of them
     * @param array $context This array is used to fill in variables in the template
     * @param string $preg_format This is the format of replacement strings in the template
     * @return string|string[]
     */
    public static function preg_template($template, array $context, string $preg_format = '/{%(\w+)(?::(\w+))?}/')
    {
        $context = array_filter($context, fn($val) => is_string($val));
        if (is_array($template)) {
            return array_map(function ($str) use ($context, $preg_format) {
                return preg_template($str, $context, $preg_format);
            }, $template);
        }
        return preg_replace_callback(
            $preg_format,
            function ($matches) use ($context) {
                $name = $matches[1];
                return $context[$name] ?? $matches[2] ?? '';
            },
            $template
        );
    }

    /**
     * Produce a string of random characters of x length
     *
     * @param int $num
     * @param string|null $chars
     * @return string
     */
    public static function random_chars(int $num, ?string $chars = null)
    {
        $chars ??= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($chars);
        $bytes = random_bytes($num);
        $result = '';
        foreach (str_split($bytes) as $byte) {
            $result .= $chars[ord($byte) % $count];
        }
        return $result;
    }

    /**
     * A factory method to create new MString objects
     *
     * @param mixed $value The value to create a new string with
     * @return MString
     */
    public static function make($value): MString
    {
        return new MString(static::toString($value));
    }

    /**
     * Convert iterable or scalar to a single string
     * Accepts either an iterable or a single value to convert to a single string
     *
     * @param iterable|mixed $strs Any value that can be converted to a string
     * @return string
     */
    public static function toString($strs)
    {
        if (!is_iterable($strs)) {
            return (string)$strs;
        }
        return Data::join($strs, '');
    }

//    public static function compare()
//    {
//
//    }
//
//    /**
//     * Returns similarity percentage
//     *
//     * @param null $algo
//     * @return int
//     */
//    public static function similarity(string $word, string $compareWord, $algo = null): int
//    {
//        if (is_null($algo)) {
//            $algo = static::BY_CHARACTERS;
//        }
//
//        if (is_string($algo) && method_exists(Str::class, $algo)) {
//   s         $algo = Closure::fromCallable([Str::class, $algo]);
//        } elseif (is_callable($algo)) {
//            $algo = Closure::fromCallable($algo);
//        } else {
//            InvalidArgumentException::raise('"%s" is not a valid similarity algorithm');
//        }
//    }

    public static function sluggify($val, $maxlen = null)
    {
        $slug = implode(
            static::SLUG_DELIM,
            u($val)->normalize()->lower()->split('/\W+/', null, PREG_SPLIT_NO_EMPTY)
        );
        if (!is_null($maxlen)) {
            return (string)u($slug)->slice(0, $maxlen);
        }
        return $slug;
    }

    // these functions allow us to compare strings using a common interface

//    function cmp_chars()
//    {
//
//    }
//
//    function cmp_levenstein()
//    {
//
//    }
//
//    function cmp_soundex()
//    {
//
//    }
//
//    function cmp_metaphone()
//    {
//
//    }

}