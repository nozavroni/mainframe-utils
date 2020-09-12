<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Validate;

use Mainframe\Utils\Data\Validate\Exception\UnknownRuleException;
use Mainframe\Utils\Data\Validate\Operator\Operator;
use Mainframe\Utils\Data\Validate\Operator\OperatorInterface;
use Mainframe\Utils\Data\Validate\Rule\RuleInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Helper\Str;

class V
{
    const REPL_FORMAT = '{%%%s}';
    const OPERATOR_NAMESPACE = '{%namespace}\\Operator\\{%name}Operator';
    const RULE_NAMESPACE = '{%namespace}\\Rule\\{%name}Rule';

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
            static::OPERATOR_NAMESPACE,
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
        $class = Str::template(
            static::RULE_NAMESPACE,
            ['name' => ucfirst($name), 'namespace' => __NAMESPACE__],
            static::REPL_FORMAT
        );

        if (class_exists($class)) {
            /** @var RuleInterface $rule */
            return new $class(...$arguments);
        }

        UnknownRuleException::raise('Unknown method: %s::%s', [__CLASS__, $name]);
    }
}