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

class GtRule implements RuleInterface
{
    protected $upper;

    public function __construct($upper)
    {
        $this->upper = value_of($upper);
    }

    public function __invoke($value): bool
    {
        return (bool) $value > $this->upper;
    }
}