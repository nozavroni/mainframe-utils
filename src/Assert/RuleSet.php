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
use Mainframe\Utils\Assert\Operator\OperatorInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Helper\Str;
use SplStack;
use function Mainframe\Utils\str;

class RuleSet implements RuleSetInterface
{
    const REPL_FORMAT = '{%%%s}';
    const OPERATOR_CLASS = '\\Mainframe\\Utils\\Assert\\Operator\\{%name}Operator';

    /**
     * A stack of operations this set will perform. Can contain both rules as well as operations
     * (which can in turn have their own sub rules)
     *
     * @var SplStack
     */
    protected SplStack $rules;

    /**
     * RuleSet constructor.
     */
    public function __construct()
    {
        $this->rules = new SplStack;
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments)
    {
        $this->rules->push(A::$name(...$arguments));
    }

    /**
     * Invoke ruleset on a value
     * Throws an AssertionFailedException if value fails assertion(s)
     *
     * @param mixed $value The value to validate
     */
    public function __invoke($value): void
    {
        if (!($value instanceof Value)) {
            $value = new Value($value);
        }
        foreach ($this->rules as $rule) {
            AssertionFailedException::assert($rule, $value);
        }
    }

    /**
     * @param mixed $value Check if a value passes all the assertions
     * @return bool
     */
    public function isValid($value): bool
    {
        return AssertionFailedException::recover (
            function () use ($value) {
                $this->__invoke($value);
                return true;
            },
            false,
            function ($exception) {
                // this is where you could do logging or something...
                // dump($exception);
            }
        );
    }
}