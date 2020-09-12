<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Strings\Regex;

use Composer\Downloader\PathDownloader;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Helper\Str;
use function Mainframe\Utils\str;

class Pattern
{
    const REPR_FORMAT = '{%%%s}';
    const MOD_CHARS = 'imsxeADSUXJu';
    const DEL_CHARS = [
        '{}', '[]', '()', '<>',
        '/', '!', '"', '#', '$', '%', '&', '\'',
        '*', '+', ',', '.', '/', ':', ';', '=',
        '?', '@', '^', '_', '`', '|', '~', '-'
    ];

    const ANCHORED_LEFT = 1;
    const ANCHORED_RIGHT = 2;
    const ANCHORED = 3;

    protected static string $defaultDelim = '/';

    /** @var string */
    protected string $pattern;

    protected string $modifiers;

    protected string $delims;

    public function __construct($pattern, $mods = null, $delims = null)
    {
        if (is_null($delims)) {
            $delims = static::$defaultDelim;
        }
        $this->pattern = (string)$pattern;
        $this->modifiers = (string)str($mods)->intersect(static::MOD_CHARS);
        $this->delims = (string)$delims;
    }

    /**
     * Pattern constructor.
     * @param string $pattern
     */
    /*    public function __construct(string $pattern)
        {
            $p = str($pattern);
            $s = $p->truncate(1)->toString();
            $p = $p->slice(1);
            InvalidArgumentException::raiseUnless(
                Data::contains(Data::map(static::DEL_CHARS, fn($v) => $v[0]), $s),
                'Invalid delimiter in provided regex pattern: %s',
                [$s]
            );
            $d = Data::first(static::DEL_CHARS, function ($v, $k, $i) use ($s) {
                if (str($v)->truncate(1)->toString() === $s) {
                    return true;
                }
            });
    //        dd(get_defined_vars());
            $e = str($d)->reverse()->truncate(1)->toString();
            InvalidArgumentException::raiseIf(
                $p->indexOfLast($e) === null,
                'Missing end delimiter: %s',
                [$e]
            );
            $this->pattern = (string)$p->beforeLast($e);
            $this->delims = (string)$d;
            $this->modifiers = (string)$p->afterLast($e)->intersect(static::MOD_CHARS);
        }*/

    /**
     * @param string $defaultDelim One or two chars to use as delim(s) (either something like / or like <>)
     */
    public static function setDefaultDelim(string $defaultDelim)
    {
        InvalidArgumentException::raiseUnless(
            Data::contains(static::DEL_CHARS, $defaultDelim),
            'Invalid delimiter. Must be one of: %s',
            [static::DEL_CHARS]
        );
        static::$defaultDelim = $defaultDelim;
    }

    public static function create(string $pattern): Pattern
    {
        $p = str($pattern);
        $s = $p->first();
        $p = $p->slice(1);
        InvalidArgumentException::raiseUnless(
            Data::contains(Data::map(static::DEL_CHARS, fn($v) => $v[0]), $s),
            'Invalid delimiter in provided regex pattern: %s',
            [$s]
        );
        $d = Data::first(static::DEL_CHARS, function ($v, $k, $i) use ($s) {
            if (str($v)->truncate(1)->toString() === $s) {
                return true;
            }
        });
        $e = str($d)->reverse()->truncate(1)->toString();
        InvalidArgumentException::raiseIf(
            $p->indexOfLast($e) === null,
            'Missing end delimiter: %s',
            [$e]
        );
        return new static(
            (string)$p->beforeLast($e),
            (string)$p->afterLast($e),
            (string)$d,
            (string)$d,
        );
    }

    /**
     * Create a pattern using constituent parts
     *
     * @param string $pattern The pattern, without delimiters
     * @param string|null $mods Modifier flag character string
     * @param int|null $anchored Whether to anchor the regex
     * @param string $delims If you want specific delimiters used, provide them here
     * @return static
     */
//    public static function create(string $pattern, ?string $mods = null, ?int $anchored = null, $delims = null): Pattern
//    {
//        if (is_null($delims)) {
//            $delims = static::$defaultDelim;
//        }
//        if (str($delims)->truncate(2)->length() !== 2) {
//            $delims .= $delims;
//        }
//        [$l, $r] = str($delims)->chunk(1);
//
//        if ($anchored) {
//            if ($anchored == static::ANCHORED_LEFT) {
//                $pattern = sprintf('^%s', $pattern);
//            }
//            if ($anchored == static::ANCHORED_RIGHT) {
//                $pattern = sprintf('%s$', $pattern);
//            }
//            if ($anchored == static::ANCHORED) {
//                $pattern = sprintf('^%s$', $pattern);
//            }
//        }
//
//        return new static(Str::template(
//            '{%left}{%pat}{%right}{%mods}',
//            [
//                'left' => (string)$l,
//                'right' => (string)$r,
//                'pat' => $pattern,
//                'mods' => (string) str($mods)->intersect(static::MOD_CHARS),
//            ],
//            static::REPR_FORMAT
//        ));
//    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return string
     */
    public function getModifiers(): string
    {
        return $this->modifiers;
    }

    /**
     * @return string
     */
    public function getDelims(): string
    {
        return $this->delims;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        $d = str($this->getDelims());
        return Str::template(
            '{%l}{%p}{%r}{%m}',
            [
                'l' => $d->first(),
                'r' => $d->last(),
                'p' => $this->getPattern(),
                'm' => $this->getModifiers(),
            ],
            static::REPR_FORMAT
        );
    }
}