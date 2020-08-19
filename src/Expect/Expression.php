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
    /** @var Closure The callback represented by this expression object */
    protected Closure $callback;

    /**
     * Expression constructor.
     * @param callable $callback
     * @param null $newthis
     */
    public function __construct(callable $callback, $newthis = null)
    {
        $this->callback = Closure::fromCallable($callback)
            /*->bindTo($newthis)*/  ;
    }

    /**
     * @param $value
     */
    public function __invoke($value)
    {
        ValidationException::raiseUnless (
            value_of($this->callback, $value),
            "fail" // @todo Come up with a better message or mechanism for one
        );
    }
}