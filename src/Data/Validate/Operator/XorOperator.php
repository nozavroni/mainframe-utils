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
 * @property Operand $left The left-hand operand
 * @property Operand $right The right-hand operand
 * @method bool left(Value $value) The left-hand operand executed
 * @method bool right(Value $value) The right-hand operand executed
 */
class XorOperator extends Operator
{
    public function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    protected function operate(Value $value): bool
    {
        return $this->left($value) xor $this->right($value);
    }
}