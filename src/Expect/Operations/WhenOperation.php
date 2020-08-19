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
 * @property bool|callable $condition
 * @property bool|callable $then
 * @property bool|callable $else
 */
class WhenOperation extends Operation
{
    public function __construct(Assert $assertion, $condition, $then, $else = false)
    {
        parent::__construct (
            $assertion,
            new Pair('condition', $condition),
            new Pair('then', $then),
            new Pair('else', $else)
        );
    }

    protected function doOperation($value): bool
    {
        if (value_of($this->condition, $value)) {
            return value_of($this->then, $value);
        }
        return value_of($this->else, $value);
    }
}