<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Operator;

use Closure;
use Mainframe\Utils\Assert\Value;

class Operand
{
    /** @var Closure The callback that drives this operand */
    protected Closure $callback;

    /**
     * Operand constructor.
     *
     * @param mixed|callable $value The value or callback
     */
    public function __construct($value)
    {
        $this->setCallback($value);
    }

    /**
     * Set callback
     *
     * @param mixed|callable $value Set the callback value
     */
    protected function setCallback($value)
    {
        if (!is_callable($value)) {
            $value = fn(...$args) => $value;
        }
        $this->callback = Closure::fromCallable($value)
            ->bindTo($this);
    }

    /**
     * Invoke is just an alias for "express" that allows the operand to work as a callable
     *
     * @param Value $value The value being validated
     * @return bool
     */
    public function __invoke(Value $value): bool
    {
        return $this->express($value);
    }

    /**
     * Express this operand
     * If the operand is a callable, pass the value to it and return its return value. Otherwise,
     * just get the boolean value of it.
     *
     * @param Value $value The value being validated
     * @return bool
     */
    public function express(Value $value): bool
    {
        $c = $this->callback;
        while (is_callable($c)) {
            $c = value_of($c, $value);
        }
        return (bool) $c;
    }
}