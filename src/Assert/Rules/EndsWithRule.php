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

use function Mainframe\Utils\str;

class EndsWithRule implements RuleInterface
{
    protected string $suffix;

    public function __construct($suffix)
    {
        $this->suffix = (string) value_of($suffix);
    }

    public function __invoke($value): bool
    {
        return (bool) str($value)->endsWith($this->suffix);
    }
}