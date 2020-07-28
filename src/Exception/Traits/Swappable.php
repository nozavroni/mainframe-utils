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
     * @param string|null $type A class or interface to swap out for the calling class
     * @throws Throwable
     * @todo Accept an argument that tells which kind(s) of exception to swap, for instance if you called
     *       a function that throws a LogicException OR a RuntimeException but you only want
     *       RuntimeException to be swapped for a DomainException you would call
     *       DomainException::swap($func, RuntimeException::class)
     */
    public static function swap(callable $func, ?string $type = null)
    {
        $class = static::class;
        TypeError::raiseUnless(
            is_subclass_of($class, Throwable::class),
            '"%s" is not a valid throwable', [$class]
        );
        return recover (
            fn () => value_of($func).
            null,
            function ($throwable) use ($class, $type) {
                // kind of a hack...using the handler argument (which is supposed to be passive)
                // to throw the desired exception
                if (is_null($type) || is_subclass_of($throwable, $type)) {
                    throw new $class($throwable->getMessage(), $throwable->getCode(), $throwable);
                }
                // if no swap can be done, rethrow the original
                throw $throwable;
            }
        );
    }
}
