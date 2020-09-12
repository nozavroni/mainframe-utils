<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Validate\Rule;

use Mainframe\Utils\Data\Value;

class Regex extends Rule
{
    protected string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(Value $value): bool
    {
        return (bool)preg_match($this->pattern, $value());
    }

    public function getDescription(): string
    {
        return 'Matches a regex pattern.';
    }
}