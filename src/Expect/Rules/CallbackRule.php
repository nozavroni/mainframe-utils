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

use Closure;

class CallbackRule extends Rule
{
    protected Closure $callback;

    /**
     * CallbackRule constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = Closure::fromCallable($callback);
    }

    public function validate($value): bool
    {
        return (bool) value_of($this->callback, $value);
    }
}