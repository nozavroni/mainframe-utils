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
use Mainframe\Utils\Assert\Exception\ValidationException;
use Mainframe\Utils\Assert\Expression;
use Mainframe\Utils\Assert\ExpressionInterface;
use Mainframe\Utils\Assert\Rules\RuleInterface;
use Mainframe\Utils\Assert\Value;
use Mainframe\Utils\Data\Collection;
use Mainframe\Utils\Data\CollectionInterface;
use Mainframe\Utils\Helper\Data;

/**
 * Operations don't necessarily need to return anything because the rules
 * that they operate on will do it.
 */
abstract class Operation
{
    protected CollectionInterface $operands;

    public function __construct(Assert $assertion, ...$operands)
    {
        $this->assertion = $assertion;
        $this->operands = new Collection;
        Data::each($operands, [$this->operands, 'push']);
    }

    public function __invoke(): callable
    {
        return $this->buildExpression();
    }

    protected function buildExpression(): ExpressionInterface
    {
        return new Expression(fn ($value) => $this->doOperation($value));
    }

    abstract protected function doOperation($value): bool;
}