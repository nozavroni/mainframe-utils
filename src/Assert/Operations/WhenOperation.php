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

class WhenOperation extends Operation
{
    protected $condition;

    protected $then;

    protected $else;

    public function __construct(Assert $assertion, $condition, $then, $else = false)
    {
        $this->assertion = $assertion;
        $this->condition = $condition;
        $this->then = $then;
        $this->else = $else;
    }

    protected function doOperation($value): bool
    {
        if (value_of($this->condition, $value)) {
            return value_of($this->then, $value);
        }
        return value_of($this->else, $value);
    }
}