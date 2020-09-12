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

/**
 * @property Operand $right The right-hand operand
 * @method bool right(Value $value) The right-hand operand executed
 */
class NullOrOperator extends Operator
{
    public function __construct($right)
    {
        $this->right = $right;
    }

    protected function operate(Value $value): bool
    {
        return $value() === null || $this->right($value);
    }
}