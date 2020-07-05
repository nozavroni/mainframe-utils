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

use Mainframe\Data\CollectionInterface;

trait ArgumentAware
{
    protected CollectionInterface $args;

    public function setArgs(iterable $args)
    {
        $this->args = collect($args);
        return $this;
    }

    public function setArg($name, $value)
    {
        $this->args->set($name, $value);
        return $this;
    }

    public function getArgs(): array
    {
        return $this->args->toArray();
    }

    public function getArg($name)
    {
        return $this->args->get($name);
    }
}