<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Operations;

use Mainframe\Utils\Assert\Value;

class AndOperation extends Operation
{
    protected function doOperation($value): bool
    {
//        return $this->operands->assert(function ($operand) use ($value) {
//            return (bool) value_of($operand, $value);
//        });
        foreach ($this->operands as $operand) {
            if (!value_of($operand, $value)) {
                return false;
            }
        }
        return true;
    }
}