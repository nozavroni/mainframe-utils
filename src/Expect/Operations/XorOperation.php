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
use Mainframe\Utils\Data\Pair;

/**
 * @property bool|callable $left
 * @property bool|callable $right
 */
class XorOperation extends Operation
{
    public function __construct(Assert $assertion, $left, $right)
    {
        parent::__construct(
            $assertion,
            new Pair('left', $left),
            new Pair('right', $right)
        );
    }

    protected function doOperation($value): bool
    {
        return value_of($this->left, $value) xor value_of($this->right, $value);
    }
}