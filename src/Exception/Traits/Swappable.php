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

use Mainframe\Utils\Exception\TypeError;
use Throwable;

trait Swappable
{
    /**
     * Swap a thrown exception
     * Run a callable, catch any exception thrown and swap it out with the exception calling this method.
     *
     * @param callable $func A callable that might throw an exception to be swapped out
     * @throws Throwable
     */
    public static function swap(callable $func)
    {
        $class = static::class;
        TypeError::raiseUnless(
            is_subclass_of($class, Throwable::class),
            '"%s" is not a valid throwable', [$class]
        );
        return recover (
            fn () => value_of($func).
            null,
            function ($throwable) use ($class) {
                // kind of a hack...using the handler argument (which is supposed to be passive)
                // to throw the desired exception
                throw new $class($throwable->getMessage(), $throwable->getCode(), $throwable);
            }
        );
    }
}
