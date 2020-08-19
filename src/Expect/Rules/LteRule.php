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

class LteRule extends Rule
{
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    protected function validate($value): bool
    {
        return $value <= value_of($this->number);
    }
}