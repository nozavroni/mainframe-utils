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

class LtRule implements RuleInterface
{
    protected $lower;

    public function __construct($lower)
    {
        $this->lower = value_of($lower);
    }

    public function __invoke($value): bool
    {
        return (bool) $value < $this->lower;
    }
}