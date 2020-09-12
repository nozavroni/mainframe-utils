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
use function Mainframe\Utils\str;

class Matches extends Rule
{
    /** @var string The pattern to match */
    protected string $pattern;

    /**
     * Rule constructor
     *
     * @param string $pattern The match pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Validate the rule
     *
     * @param Value $value The value to validate
     * @return bool
     */
    public function validate(Value $value): bool
    {
        return (bool)preg_match("/^{$this->pattern}$/", $value());
    }

    /**
     * Get a human-friendly description for this rule
     *
     * @return string
     */
    public function getDescription(): string
    {
        return
            'Matches a regular expression pattern. Automatically adds start and end anchors if not provided. If you ' .
            'need to match a pattern without the anchors, use the Regex rule instead.';
    }
}