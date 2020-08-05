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

class MatchRule implements RuleInterface
{
    protected string $pattern;

    public function __construct($pattern)
    {
        $this->pattern = (string) value_of($pattern);
    }

    public function __invoke($value): bool
    {
        return (bool) preg_match($this->pattern, $value);
    }
}