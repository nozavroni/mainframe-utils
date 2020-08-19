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

class OrOperation extends Operation
{
    protected function doOperation($value): bool
    {
        foreach ($this->operands as $key => $operand) {
            if (value_of($operand, $value)) {
                return true;
            }
        }
        return false;
    }
}