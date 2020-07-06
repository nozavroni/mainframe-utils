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

class OutOfBoundsException extends \OutOfBoundsException implements RaisableInterface, RecoverableInterface, SuppressableInterface
{
    use Traits\Raisable,
        Traits\Recoverable,
        Traits\Suppressable;
}