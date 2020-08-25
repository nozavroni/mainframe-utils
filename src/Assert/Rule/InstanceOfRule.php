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

use Mainframe\Utils\Assert\Exception\RuleException;
use Mainframe\Utils\Assert\Value;
use Mainframe\Utils\Helper\Data;

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
        $this->classes = Data::map (
            $class,
            function ($class, $k, $i) {
                if (!is_string($class)) {
                    RuleException::raiseUnless(is_object($class), 'Invalid argument for {%class}');
                    $class = get_class($class);
                }
                RuleException::raiseUnless(class_exists($class) || interface_exists($class), 'Invalid class/interface name: %s', [$class]);
                return $class;
            }
        );
    }

    public function validate(Value $value): bool
    {
        return Data::any($this->classes, fn ($class, $k, $i) => $value() instanceof $class);
    }

    /**
     * Get a human-friendly description for this rule
     *
     * @return string
     */
    public function getDescription(): string
    {
        return
            'Assert that value is an instance of a given class or object. Can provide an array of classes and/or ' .
            'objects if you would like to assert the value is an instance of one of them.';
    }


}