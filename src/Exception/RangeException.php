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
 * Exception thrown to indicate range errors during program execution. Normally this means there was an arithmetic error
 * other than under/overflow. This is the runtime version of DomainException.
 */
class RangeException extends \RangeException implements RaisableInterface, RecoverableInterface, SuppressableInterface
{
    use Traits\Raisable,
        Traits\Recoverable,
        Traits\Suppressable;
}