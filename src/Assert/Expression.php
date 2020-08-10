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

class Expression implements ExpressionInterface
{
    protected Closure $callback;

    public function __construct(callable $callback)
    {
        $this->callback = Closure::fromCallable($callback);
    }

    public function __invoke($value)
    {
        ValidationException::raiseUnless(value_of($this->callback, $value), "fail");
    }
}