<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Operations;

use Closure;
use Mainframe\Utils\Assert\Assert;
use Mainframe\Utils\Assert\AssertStack;
use Mainframe\Utils\Assert\Exception\UnknownOperandException;
use Mainframe\Utils\Assert\Exception\ValidationException;
use Mainframe\Utils\Assert\Expression;
use Mainframe\Utils\Assert\ExpressionInterface;
use Mainframe\Utils\Data\Collection;
use Mainframe\Utils\Data\CollectionInterface;
use Mainframe\Utils\Data\Pair;

/**
 * Operations don't necessarily need to return anything because the rules
 * that they operate on will do it.
 */
abstract class Operation
{
    /** @var CollectionInterface A container for each of the operation's operands */
    protected CollectionInterface $operands;

    protected AssertStack $assertstack;

    /**
     * Operation constructor.
     * @param Assert $assertion
     * @param mixed ...$operands
     */
    public function __construct(Assert $assertion, ...$operands)
    {
        $this->assertion = $assertion;
        $this->assertstack = new AssertStack();
        $this->operands = Collection::create();
        foreach ($operands as $key => $operand) {
            $this->operand($operand);
        }
    }

    /**
     * @param $operand
     */
    protected function operand($operand)
    {
        $name = null;
        if ($operand instanceof Pair) {
            list($name, $operand) = $operand->toArray();
        }
        if (is_callable($operand)) {
            $operand = Closure::fromCallable(function ($value) use ($operand) {
                ValidationException::raiseUnless(fn() => value_of($operand, $this->assertion), 'fail');
                return true;
            });
        }
        if (is_null($name)) {
            $this->operands->push($operand);
        } else {
            $this->operands->set($name, $operand);
        }
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __get($name)
    {
        if (!$this->operands->has($name)) {
            UnknownOperandException::unknown($name);
        }
        return $this->operands->get($name);
    }

    /**
     * @return callable
     */
    public function __invoke(): callable
    {
        return $this->buildExpression();
    }

    /**
     * @return ExpressionInterface
     */
    protected function buildExpression(): ExpressionInterface
    {
        return new Expression (
            fn ($value) => $this->doOperation($value),
            $this->assertion
        );
    }

    /**
     * @param $value
     * @return bool
     */
    abstract protected function doOperation($value): bool;
}