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

class BetweenRule implements RuleInterface
{
    protected $min;

    protected $max;

    public function __construct($min, $max)
    {
        $this->min = value_of($min);
        $this->max = value_of($max);
    }

    public function __invoke($value): bool
    {
        return ($value > $this->min && $value < $this->max);
    }
}