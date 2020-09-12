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

/**
 * @property Operand $left The left-hand operand
 * @property Operand $right The right-hand operand
 * @method bool left(Value $value) The left-hand operand executed
 * @method bool right(Value $value) The right-hand operand executed
 */
class OrOperator extends Operator
{
    protected function operate(Value $value): bool
    {
        return Data::any($this->operands, fn($o) => value_of($o, $value));
    }
}