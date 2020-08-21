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
        $this->setOperand($value, $name);
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
     * Allows the calling of operands as methods
     * If you call an operand using property syntax, you will get the callback it consists of. If you
     * call it using method syntax, it will actually invoke the callback using whatever value you pass
     * to it (because of this method)
     *
     * @param $name string The operand name (or position)
     * @param $arguments array The arguments to pass to the operand
     * @return bool
     * @throws BadMethodCallException If $name is not a valid operand name/position.
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
     * @return Closure
     */
    public function __invoke(): Closure
    {
        $closure = Closure::fromCallable([$this, 'operate']);
        return $closure;
    }

    /**
     * Perform the operation
     *
     * @param Value $value The value to validate
     * @return bool
     */
    abstract protected function operate(Value $value): bool;

}