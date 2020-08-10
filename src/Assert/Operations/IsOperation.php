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

class IsOperation extends Operation
{
    protected $test;

    /**
     * IsOperation constructor.
     * @param Assert $assertion
     * @param bool|callable $test
     */
    public function __construct(Assert $assertion, $test)
    {
        $this->test = $test;
        $this->assertion = $assertion;
    }

    protected function doOperation($value): bool
    {
        return (bool) value_of($this->test, $value);
    }
}