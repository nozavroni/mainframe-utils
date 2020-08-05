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

class AndRule implements RuleInterface
{
    protected array $callables;

    public function __construct(...$callbacks)
    {
        $this->callables = $callbacks;
    }

    public function __invoke($value)
    {
        foreach ($this->callables as $func) {
            if (!$func($value)) {
                return false;
            }
        }
        return true;
    }
}