<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Rules;

use Mainframe\Utils\Assert\Assert;

class BetweenRule extends Rule
{
    protected $low;

    protected $high;

    public function __construct(Assert $assert, $low, $high)
    {
        $this->assert = $assert;
        $this->low = $low;
        $this->high = $high;
    }

    protected function validate($value): bool
    {
        return $value < value_of($this->high) && $value > value_of($this->low);
    }
}