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

namespace Mainframe\Support\Action\Traits;

use Mainframe\Utils\Helper\Data;

trait ArgumentAware
{
    protected array $args = [];

    public function setArgs(iterable $args)
    {
        $this->args = Data::toIndex($args);
        return $this;
    }

    public function setArg($name, $value)
    {
        Data::set($this->args, $name, $value);
        return $this;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getArg($name)
    {
        return Data::get($this->args, $name);
    }
}