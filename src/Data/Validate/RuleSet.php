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

use Mainframe\Utils\Data\Validate\Exception\AssertionFailedException;
use Mainframe\Utils\Data\Value;
use SplStack;

class RuleSet implements RuleSetInterface
{
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
        $this->rules->push(V::$name(...$arguments));
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