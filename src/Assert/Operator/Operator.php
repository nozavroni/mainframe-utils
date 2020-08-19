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
use Mainframe\Utils\Assert\RuleSet;
use Mainframe\Utils\Assert\RuleSetInterface;
use Mainframe\Utils\Assert\Value;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Helper\Data;

abstract class Operator implements OperatorInterface
{
    /** @var Operand[] The operand that was passed in from the assertion */
    protected array $operands = [];

    /**
     * Operator constructor
     */
    public function __construct(...$operands)
    {
        foreach ($operands as $index => $operand) {
            $this->setOperand($operand, $index);
        }
    }

    public function setOperand($operand, $name): self
    {
        $operand = new Operand($operand);
        Data::set($this->operands, $name, $operand);
        return $this;
    }

    /**
     * run when writing data to inaccessible members.
     *
     * @param $name string
     * @param $value mixed
     * @return void
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __set($name, $value)
    {
        Data::set($this->operands, $name, $value);
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
        $operand = Data::get($this->operands, $name);
        return $operand;
    }

    /**
     * Allows the calling of operands as methods [ $this->left($value) ]
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     */
    public function __call($name, $arguments): bool
    {
        if (Data::has($this->operands, $name)) {
            return (bool) value_of(Data::get($this->operands, $name), ...$arguments);
        }

        BadMethodCallException::raise('Unknown method: %s::%s', [__CLASS__, $name]);
    }

    /**
     * Returns a closure that does the actual operation
     * The closure accepts a Value object which it can use to perform validation checks and such.
     * The closure is also bound to its local rule set so that you can do more operations using $this
     *
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     */
    public function __invoke(): Closure
    {
        $closure = Closure::fromCallable([$this, 'operate']);
        return $closure;
    }

    abstract protected function operate(Value $value): bool;

}