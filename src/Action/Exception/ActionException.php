<?php
/**
 * Mainframe - a domain codification framework
 *
 * A clumsy attempt to put a name to a concept I've been kicking around for quite some time now. See the
 * README file for a more in-depth overview of this concept and how this library relates to it.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Action\Exception;

use InvalidArgumentException;
use Mainframe\Utils\Exception\Exception;

/**
 * ActionException - allows you to defer an action by throwing it like an exception
 * All exceptions that actually DO things should extend this class. For instance, if an exception is thrown
 * to cause a redirect, or to break from a loop or for anything other than error handling. These exceptions can accept
 * an array of arguments which can then be used by whatever catches the exception.
 */
class ActionException extends Exception
{
    /** @var callable The action to perform once exception is caught */
    protected $action;

    /** @var array The instruct arguments */
    protected array $args = [];

    /**
     * @param callable|null $action The action (a callable)
     * @param array|null $args The action arguments
     * @param string $message An exception message
     * @throws ActionException
     */
    public static function throw($action = null, ?array $args = null, $message = '')
    {
        if (is_null($args)) {
            $args = [];
        }
        // we don't give a shit about any of this stuff
        $exception = new static($message);
        // this is what we care about...
        $exception
            ->setAction($action)
            ->setArgs($args);
        throw $exception;
    }

    /**
     * @return callable|null
     */
    public function getAction(): ?callable
    {
        return $this->action;
    }

    /**
     * @param callable|null $action
     * @return $this
     */
    public function setAction(?callable $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get an array of arguments
     *
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Set the args
     *
     * @param array $args The arguments to set
     * @return $this
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }

    /**
     * Set a single argument
     *
     * @param string $name The argument name
     * @param mixed $value The argument value
     */
    public function setArg($name, $value)
    {
        $this->args[$name] = $value;
    }

    /**
     * Get a single argument value
     *
     * @param string $name The argument name you want
     * @return mixed
     */
    public function getArg($name)
    {
        if (!array_key_exists($name, $this->args)) {
            throw new InvalidArgumentException(sprintf('Cannot get unknown argument "%s"', $name));
        }
        return $this->args[$name];
    }

    public function __invoke(array $args = [], $replaceArgs = false)
    {
        if (is_callable($this->action)) {
            return call_user_func_array(
                $this->action,
                $replaceArgs ? $args : [$this->getArgs(), ...$args]
            );
        }
    }

}