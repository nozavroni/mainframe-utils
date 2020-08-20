<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Rule;

use Mainframe\Utils\Assert\Value;

class RegexRule extends Rule
{
    protected string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(Value $value): bool
    {
        $pattern = preg_quote($this->pattern, '/');
        return (bool) preg_match("/{$pattern}/", $value());
    }

    public function getDescription(): string
    {
        return 'Matches a regex pattern.';
    }
}