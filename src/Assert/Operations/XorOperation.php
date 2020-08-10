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

use Mainframe\Utils\Assert\Assert;
use Mainframe\Utils\Assert\Value;

class XorOperation extends Operation
{
    protected $left;

    protected $right;

    public function __construct(Assert $assertion, $left, $right)
    {
        $this->assertion = $assertion;
        $this->left = $left;
        $this->right = $right;
    }

    protected function doOperation($value): bool
    {
        return value_of($this->left, $value) xor value_of($this->right, $value);
    }
}