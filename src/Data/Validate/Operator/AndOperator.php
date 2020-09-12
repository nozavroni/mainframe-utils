<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Validate\Operator;

use Mainframe\Utils\Data\Validate\RuleSetInterface;
use Mainframe\Utils\Data\Value;
use Mainframe\Utils\Helper\Data;

class AndOperator extends Operator
{
    protected RuleSetInterface $ruleset;

    protected function operate(Value $value): bool
    {
        return Data::assert($this->operands, fn ($o) => value_of($o, $value));
    }
}