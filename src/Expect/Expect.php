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
use Mainframe\Utils\Assert\Exception\ValidationException;
use Mainframe\Utils\Assert\Rules\RuleInterface;
use Mainframe\Utils\Data\Collection;
use Mainframe\Utils\Data\Index;
use Mainframe\Utils\Data\IndexInterface;
use Mainframe\Utils\Exception\AssertionException;
use Mainframe\Utils\Helper\Str;
use SplStack;

/**
 * @todo Look into the possibility of generating these via a composer script or something so that
 *       I don't have to maintain them manually but rather have them generated from the various
 *       operation and rule classes.
 *
 * @method self and(...$conditions)
 * @method self or(...$conditions)
 * @method self xor($left, $right)
 * @method self when(callable $condition, callable $then, callable $else)
 * @method self unless(callable $condition, callable $then)
 * @method self not(callable $condition)
 * @method self is(callable $condition)
 *
 * @method bool static between($min, $max)
 */
class Assert extends SplStack
{
    const REPL_FORMAT = '{%%%s}';

    const OPERATION_CLASS = '\\Mainframe\\Utils\\Assert\\Operations\\{%name}Operation';

    const RULES_CLASS = '\\Mainframe\\Utils\\Assert\\Rules\\{%name}Rule';

    /** @var IndexInterface Exceptions thrown by this assertion stack */
    protected IndexInterface $errors;

    /**
     * Assert constructor
     */
    public function __construct()
    {
        $this->errors = new Index();
    }

    /**
     * Proxy to Operation classes
     *
     * @todo It might be good to create a script that runs on composer install that compiles all of
     *       the operations into like a single class and the rules into another or something. The idea
     *       would be to avoid searching for the right class every time one of them is requested. Or
     *       some other means of caching/compiling.
     *
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments): self
    {
        $class = Str::template(
            static::OPERATION_CLASS,
            compact('name'),
            static::REPL_FORMAT
        );

        if (class_exists($class)) {
            // every time an operation is called, it gets dropped into the assertions collection
            // everything in there is in the form of a callback so all logic is deferred until a
            // value becomes available.
            $operation = new $class($this, ...$arguments);
            $this->callstack->push($operation());
        }

        return $this;
    }

    /**
     * is triggered when invoking inaccessible methods in a static context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public static function __callStatic($name, $arguments)
    {
        $class = Str::template(
            static::RULES_CLASS,
            compact('name'),
            static::REPL_FORMAT
        );

        if (class_exists($class)) {
            /** @var RuleInterface $rule */
            $rule = new $class(...$arguments);
            return $rule;
        }
    }

    public function registerError(AssertionException $exception)
    {
        $this->errors->push($exception);
    }

    /**
     * This method is just here so that object can be treated as a callable
     *
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     */
    public function __invoke($value)
    {
        while (!$this->callstack->isEmpty()) {
            $assertion = $this->callstack->pop();
            if (!ValidationException::recover (
                function () use ($assertion, $value) {
                    value_of($assertion, $value);
                    return true;
                },
                true, // @note this is really important - it allows for exceptions to be thrown OR for assertion to return false to do the same thing without duplicating the exception in the errors array
                fn ($error) => $this->registerError($error)
            )) {
                $this->registerError(ValidationException::create('Failed and stuff'));
            }
        }
    }

    /**
     * Perform assertion
     * Calls assert and returns false if validation exception is thrown.
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        value_of($this, $value);
        dd($this->errors);
        return $this->errors->isEmpty();
    }
}