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
 * @property bool|callable $test
 */
class IsOperation extends Operation
{
    /**
     * IsOperation constructor.
     * @param Assert $assertion
     * @param bool|callable $test
     */
    public function __construct(Assert $assertion, $test)
    {
        parent::__construct (
            $assertion,
            new Pair('test', $test)
        );
    }

    /**
     * @param mixed $value The value the operation is applied to
     * @return bool
     */
    protected function doOperation($value): bool
    {
        return (bool) value_of($this->test, $value);
    }
}