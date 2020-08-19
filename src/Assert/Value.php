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

use Closure;
use Mainframe\Utils\Assert\Exception\AssertionFailedException;
use Mainframe\Utils\Assert\Exception\UnknownRuleException;
use Mainframe\Utils\Assert\Operator\OperatorInterface;
use Mainframe\Utils\Assert\Rule\RuleInterface;
use Mainframe\Utils\Helper\Str;

class Value
{
    const REPL_FORMAT = '{%%%s}';
    const RULE_CLASS = '{%namespace}\\Rule\\{%name}Rule';

    /** @var callable The value as a callback so that it can be filled in later */
    protected $value;

    /** @var RuleSetInterface|null The rules associated with this value */
    protected ?RuleSetInterface $rules;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments): bool
    {
        $class = Str::template(
            static::RULE_CLASS,
            ['name' => ucfirst($name), 'namespace' => __NAMESPACE__],
            static::REPL_FORMAT
        );

        if (class_exists($class)) {
            /** @var RuleInterface $rule */
            $rule = new $class(...$arguments);
            return $rule->validate($this);
        }

        UnknownRuleException::raise('Unknown method: %s::%s', [__CLASS__, $name]);
    }

    /**
     * Invoking the value as if it were a function will produce its value
     */
    public function __invoke()
    {
        return value_of($this->getValue());
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return (string) value_of($this->getValue());
    }
}