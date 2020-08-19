<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Rules;

use Closure;
use Mainframe\Utils\Assert\Assert;
use Mainframe\Utils\Assert\Exception\ValidationException;
use Mainframe\Utils\Assert\Value;

abstract class Rule implements RuleInterface
{
    public function __invoke(Assert $assert)
    {
//        return ValidationException::recover(
//            function () use ($value) {
//                // I realize this is a bit odd but it allows for validate() to throw an exception OR return false
//                // it would throw an exception if it wanted to provide more info than just FALSE
//                ValidationException::raiseUnless (
//                    $this->validate($value->getValue()),
//                    'Validation failed'
//                );
//                return true;
//            },
//            false,
//            [$value, 'registerError']
//        );
        return Closure::fromCallable([$this, 'assert']);
    }

    public function assert(Value $value): void
    {

    }

    abstract protected function validate($value): bool;
}