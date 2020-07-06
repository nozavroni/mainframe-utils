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

use Closure;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Strings\MString;
use function Symfony\Component\String\u;

class Str
{
    const SLUG_DELIM = '-';

    public static function template($template, array $context, string $repl_format = '%s')
    {
        $context = array_filter($context, fn ($val) => is_string($val));
        if (is_array($template)) {
            return array_map(function($str) use ($context, $repl_format) {
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

    public static function preg_template($template, array $context, string $preg_format = '/{%(\w+)(?::(\w+))?}/')
    {
        $context = array_filter($context, fn ($val) => is_string($val));
        if (is_array($template)) {
            return array_map(function($str) use ($context, $preg_format) {
                return preg_template($str, $context, $preg_format);
            }, $template);
        }
        return preg_replace_callback(
            $preg_format,
            function($matches) use ($context) {
                $name = $matches[1];
                return $context[$name] ?? $matches[2] ?? '';
            },
            $template
        );
    }

    public static function random_chars($num, $chars = null)
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
     * @param mixed $value The value to create a new string wt=ijh2kojkooo
     * @return MString
     */
    public static function make($value): MString
    {
        return new MString(u($value));
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
//            $algo = Closure::fromCallable([Str::class, $algo]);
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
            return (string) u($slug)->slice(0, $maxlen);
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