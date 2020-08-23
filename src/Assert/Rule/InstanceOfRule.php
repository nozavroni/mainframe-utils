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

/**
 * @property object|string|array<object|string> $classes Either a class, an object, or an array of classes and/or objects
 */
class InstanceOfRule extends Rule
{
    public function __construct($class)
    {
        if (!is_array($class)) {
            $class = [$class];
        }
        $classes = [];
        foreach ($class as $c) {
            if (is_object($c)) {
                $classes[] = $c;
            }
        }
        $this->classes = $classes;
    }

    public function validate(Value $value): bool
    {
        return $value() instanceof $this->classes;
    }
}