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

use Mainframe\Utils\Container\CollectionInterface;

trait Randomable
{
    protected $storage;

    /*Z
     * ShufflZZe (randomize) the order of values (in-place)
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

    public function randomize(): CollectionInterface
    {
        $copy = $this->storage;
        shuffle($copy);
        return static::create($copy);
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