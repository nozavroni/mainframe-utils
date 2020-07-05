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

use Throwable;

/**
 * Used to return a value from deep within a loop
 */
class ReturnException extends ActionException
{
    /** @var mixed */
    protected $return;

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param mixed $return The return value
     * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct($return, Throwable $previous = null)
    {
        parent::__construct('', 0, $previous);
        $this->return = $return;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }
}