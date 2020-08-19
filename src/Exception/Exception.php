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
 * The most generic possible exception type
 */

class Exception
extends \Exception
implements RaisableInterface, RecoverableInterface, SuppressableInterface, SwappableInterface
{
    use Traits\Raisable,
        Traits\Swappable,
        Traits\Recoverable,
        Traits\Suppressable;
}
