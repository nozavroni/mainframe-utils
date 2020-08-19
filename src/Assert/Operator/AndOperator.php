<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Operator;

use Mainframe\Utils\Assert\RuleSetInterface;
use Mainframe\Utils\Assert\Value;
use Mainframe\Utils\Helper\Data;

class AndOperator extends Operator
{
    protected RuleSetInterface $ruleset;

    protected function operate(Value $value): bool
    {
        return Data::assert($this->operands, fn ($o) => value_of($o, $value));
    }
}