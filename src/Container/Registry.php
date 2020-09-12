<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Container;

use Mainframe\Utils\Helper\Data;
use SplObjectStorage;

class Registry
{
    /** @var SplObjectStorage[RegisterableInterface] */
    protected SplObjectStorage $storage;

    /** @var RegisterableInterface[] */
    protected array $mapper = [];

    public function __construct(iterable $items = [])
    {
        $this->storage = new SplObjectStorage();
        $this->registerAll($items);
    }

    public function registerAll(iterable $items): self
    {
        Data::each($items, [$this, 'register']);
        return $this;
    }

    public function register(RegisterableInterface $item): self
    {
        $this->storage->attach($item);
        $this->mapper[(string)$item] = spl_object_hash($item);
        return $this;
    }

    public function fetch(RegisterableInterface $item)
    {
        return $this->storage[$item];
    }

    public function lookup(string $id): RegisterableInterface
    {
        return $this->storage[$this->mapper[$id]];
    }

    public function detach(RegisterableInterface $item): RegisterableInterface
    {
        unset($this->mapper[(string)$item]);
        $this->storage->detach($item);
        return $item;
    }

    public function pull(string $id): RegisterableInterface
    {
        return $this->detach($this->lookup($id));
    }
}