<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Assert;

use Mainframe\Utils\Assert\Exception\UnknownRuleException;
use Mainframe\Utils\Assert\Operator\Operator;
use Mainframe\Utils\Assert\Operator\OperatorInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Helper\Str;

class A
{
    const REPL_FORMAT = '{%%%s}';
    const OPERATOR_CLASS = '{%namespace}\\Operator\\{%name}Operator';
    const RULE_CLASS = '{%namespace}\\Rule\\{%name}Rule';

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public static function __callStatic($name, $arguments)
    {
        return static::operator($name, $arguments);
    }

    public static function operator($name, array $arguments)
    {
        $class = Str::template(
            static::OPERATOR_CLASS,
            ['namespace' => __NAMESPACE__, 'name' => $name],
            static::REPL_FORMAT
        );

        if (class_exists($class)) {
            /** @var Operator $operator */
            $callback = new $class(...$arguments);
            return $callback();
        }

        UnknownRuleException::raise('Unknown rule: %s', [$name]);
    }

    public static function rule($name, array $arguments)
    {

    }
}