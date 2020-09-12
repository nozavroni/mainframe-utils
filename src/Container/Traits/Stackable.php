<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Container\Traits;

use ArrayAccess;
use Mainframe\Utils\Container\Exception\InvalidStructureException;
use Mainframe\Utils\Helper\Data;

trait Stackable
{
    /** @var array The underlying items */
    protected $storage;

    /**
     * Pop an item off the end
     *
     * @return mixed
     */
    public function pop()
    {
        if (method_exists($this->storage, 'pop')) {
            return $this->storage->pop();
        }
        if ($this->storage instanceof ArrayAccess || is_array($this->storage)) {
            return array_pop($this->storage);
        }
        InvalidStructureException::raise('Storage for %s must allow %s', [__CLASS__, __FUNCTION__]);
    }

    public function unshift($item): int
    {
        if (method_exists($this->storage, 'unshift')) {
            return $this->storage->unshift($item);
        }
        if ($this->storage instanceof ArrayAccess || is_array($this->storage)) {
            return array_unshift($this->storage, $item);
        }
        InvalidStructureException::raise('Storage for %s must allow %s', [__CLASS__, __FUNCTION__]);
    }

    public function push($item): int
    {
        if (method_exists($this->storage, 'push')) {
            return $this->storage->push($item);
        }
        if ($this->storage instanceof ArrayAccess || is_array($this->storage)) {
            return array_push($this->storage, $item);
        }
        InvalidStructureException::raise('Storage for %s must allow %s', [__CLASS__, __FUNCTION__]);
    }

    public function shift()
    {
        if (method_exists($this->storage, 'shift')) {
            return $this->storage->shift();
        }
        if ($this->storage instanceof ArrayAccess || is_array($this->storage)) {
            return array_shift($this->storage);
        }
        InvalidStructureException::raise('Storage for %s must allow %s', [__CLASS__, __FUNCTION__]);
    }

    public function top()
    {
        return Data::getByPos($this->storage, 1);
    }

    public function bottom()
    {
        return Data::getByPos($this->storage, -1);
    }
}