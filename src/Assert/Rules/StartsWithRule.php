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

class StartsWithRule implements RuleInterface
{
    protected string $prefix;

    public function __construct($prefix)
    {
        $this->prefix = (string) value_of($prefix);
    }

    public function __invoke($value): bool
    {
        return (bool) str($value)->startsWith($this->prefix);
    }
}