<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Validate\Exception;

use Mainframe\Utils\Exception\AssertionException;
use ReflectionFunction;
use function Mainframe\Utils\str;

class AssertionFailedException extends AssertionException
{
    public static function assert(callable $assertion, $value)
    {
        if (!value_of($assertion, $value)) {
            $info = new ReflectionFunction($assertion);
            $opname = str(get_class($info->getClosureThis()))
                ->afterLast('\\')
                ->replace('Operator', ' Operator');
            static::raise(
                '%s assertion failed for: %s %s',
                [
                    // $info->getClosureScopeClass(),
                    $opname,
                    $info->getName(),
                    $info->getParameters()[0]->getName(),
                    $value
                ]
            );
        }
    }
}