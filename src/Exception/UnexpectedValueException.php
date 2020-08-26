<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Exception;

/**
 * Unexpected Value Exception
 *
 * Exception thrown if a value does not match with a set of values. Typically this happens when a
 * function calls another function and expects the return value to be of a certain type or value
 * not including arithmetic or buffer related errors.
 *
 * From what I gather, this is essentially the runtime version of InvalidArgumentException (which
 * is compile-time). So if you cannot know whether the value is expected unless in
 */
class UnexpectedValueException
extends \UnexpectedValueException
implements RaisableInterface, RecoverableInterface, SuppressableInterface
{
    use Traits\Raisable,
        Traits\Swappable,
        Traits\Recoverable,
        Traits\Suppressable;

    protected static $defaultMsg = 'Unexpected value';
}