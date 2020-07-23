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

trait Accessors
{
    protected $storage;

    /**
     * Is the container empty?
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return Data::isEmpty($this->storage);
    }

    /**
     * Get values as an array
     *
     * @return array
     */
    public function values(): array
    {
        return Data::values($this->storage);
    }

    /**
     * Get keys as an array
     *
     * @return array
     */
    public function keys(): array
    {
        return Data::keys($this->storage);
    }

    /**
     * Return an array of key/value pairs
     *
     * @return array
     */
    public function pairs(): array
    {
        // TODO: Implement pairs() method.
    }
    /**
     * Get a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @param mixed $default If key is not found, this value is returned
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        return Data::get($this->items, $key, $default);
    }

    /**
     * Set a value by key / dot notation path
     *
     * @param mixed $value The value to set it to
     * @return $this
     */
    public function set($key = null, $value = null, $overwrite = true)
    {
        if (is_null($key)) {
            array_push($this->items, $value);
        }
        if ($overwrite || !$this->has($key)) {
            Data::set($this->items, $key, $value);
        }

        return $this;
    }

    /**
     * Check if there is a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @return bool
     */
    public function has($key): bool
    {
        return Data::has($this->items, $key);
    }

    /**
     * Check if there is a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @return $this
     */
    public function delete($key = null)
    {
        if (is_null($key)) {
            array_pop($this->items);
        }
        Data::delete($this->items, $key);
        return $this;
    }

    /**
     * pull a key from container (delete it) and return it
     * @param $key
     */
    public function pull($key = null)
    {
        if (is_null($key)) {
            return array_pop($this->items);
        }
        $value = $this->get($key);
        $this->delete($key);
        return $value;
    }

    /**
     * Get a random value from the collection
     *
     * @return mixed
     */
    // @todo put this into like maybe uhh... I dunno... maybe put it with getValueAt?
//    public function random()
//    {
//        return $this->getValueAt(rand(1, $this->count()));
//    }

    public function clear()
    {
        Data::clear($this->items);
        return $this;
    }

    public function toArray(): array
    {
        return Data::toArray($this->items);
    }
}