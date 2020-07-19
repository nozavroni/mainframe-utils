<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Traits;

use Mainframe\Utils\Helper\Data;

trait Stackable
{
    /** @var iterable The underlying items */
    protected $items;

    /**
     * Pop an item off the end
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    public function unshift($item): int
    {
        return array_unshift($this->items, $item);
    }

    public function push($item): int
    {
        return array_push($this->items, $item);
    }

    public function shift()
    {
        return array_shift($this->items);
    }

    public function peekLeft()
    {
        return Data::getByPos($this->items, 1);
    }

    public function peekRight()
    {
        return Data::getByPos($this->items, -1);
    }
}