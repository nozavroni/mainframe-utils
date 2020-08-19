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

class IBetweenRule extends Rule
{
    protected $low;

    protected $high;

    public function __construct($low, $high)
    {
        $this->low = $low;
        $this->high = $high;
    }

    protected function validate($value): bool
    {
        return $value <= value_of($this->high) && $value >= value_of($this->low);
    }
}