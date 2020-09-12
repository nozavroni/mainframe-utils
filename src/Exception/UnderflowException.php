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
 * Exception thrown when you try to remove an element of an empty container.
 */
class UnderflowException
    extends \UnderflowException
    implements RaisableInterface, RecoverableInterface, SuppressableInterface, SwappableInterface
{
    use Traits\Raisable,
        Traits\Swappable,
        Traits\Recoverable,
        Traits\Suppressable;

    public static function getDefaultMessage(): string
    {
        return 'Cannot remove an item from an empty container';
    }
}