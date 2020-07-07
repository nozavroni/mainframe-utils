<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Exception\Traits;

use DateTime;
use Mainframe\Utils\Exception\RaisableInterface;
use Mainframe\Utils\Helper\Str;
use Throwable;

const DATE = 'Y-m-d';
const TIME = 'g:i:sa';

trait Raisable
{
    /**
     * Essentially this is just a different way to throw an exception where no message is required, in fact
     * no arguments at all are. And the exception message works as sprintf() does. This just makes it cleaner
     * to do things like InvalidArgumentException::raise('"%s" is not a valid argument');
     *
     * In addition, there a number of pre-defined values that do not need to be specified in $args. Those are:
     *
     *      {%type} - exception type
     *      {%date} - current date
     *      {%time} - current time
     *      {%datetime} - current date/time
     *      {%timestamp} - current date/time timestamp
     *      {%previous} - previous exception type (or none if not one)
     *
     * @param string $str
     * @param array $args
     * @param Throwable|null $throwable
     * @throws RaisableInterface
     */
    public static function raise(?string $str = null, array $args = [], ?Throwable $throwable = null): void
    {
        throw static::create($str, $args, $throwable);
    }

    public static function raiseIf($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null)
    {
        if (value_of($condition)) {
            static::raise($str, $args, $throwable);
        }
    }

    public static function raiseUnless($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null)
    {
        if (!value_of($condition)) {
            static::raise($str, $args, $throwable);
        }
    }

    // @todo I had wanted to do this so that isRaised would always be right, but I suppose it still will be
    //      as raised is somewhat different than thrown
//    private function __construct($message = null, $code = 0, Throwable $throwable = null)
//    {
//        parent::__construct((string) $message, $code, $throwable);
//    }

    public static function create(?string $str = null, array $args = [], ?Throwable $throwable = null): RaisableInterface
    {
        $dt = new DateTime;
        $predefined = [
            'type' => parent::class,
            'date' => $dt->format(DATE),
            'time' => $dt->format(TIME),
            'datetime' => $dt->format(DATE . ' ' . TIME),
            'timestamp' => $dt->getTimestamp(),
            'previous' => is_object($throwable) ? get_class($throwable) : 'unknown',
        ];

        return new static (
            vsprintf(
                Str::template($str, $args + $predefined, '{%%%s}'),
                array_filter($args, fn ($key) => is_int($key), ARRAY_FILTER_USE_KEY)
            ),
            0,
            $throwable
        );
    }
}