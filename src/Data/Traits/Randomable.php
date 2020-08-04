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

use Mainframe\Utils\Data\RandomableInterface;
use Mainframe\Utils\Helper\Data;

trait Randomable
{
    protected $storage;

    /**
     * Shuffle (randomize) the order of values (in-place)
     *
     * @return $this
     */
    public function shuffle()
    {
        $new = [];
        $keys = array_keys($this->storage);
        shuffle($keys);
        foreach ($keys as $key) {
            $new[$key] = $this->storage[$key];
        }
        $this->storage = $new;

        return $this;
    }

    public function randomize(): Randomable
    {
        $copy = $this->storage;
        shuffle($copy);
        return new static($copy);
    }

    /**
     * Pick and return one item at random
     */
    public function random()
    {
        return $this->storage[$this->randomKey()];
    }

    /**
     * Pick a random key/offset and return it
     */
    public function randomKey()
    {
        return array_rand($this->storage);
    }
}