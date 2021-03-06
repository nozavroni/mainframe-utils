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
namespace Mainframe\Support\Action;

abstract class AbstractAction
{
    /**
     * Invoke the action
     *
     * @param mixed $args,... Allow unlimited args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->perform(...$args);
    }

    /**
     * Perform the action
     *
     * @param mixed $args,... Allow unlimited args
     * @return mixed
     */
    abstract protected function perform(...$args);

}