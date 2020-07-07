<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data;

use SplFixedArray;

/**
 * Essentially a two-item tuple, usually used to represent a key/value pair
 */
class Pair extends Tuple
{
    public function __construct($name, $value)
    {
        $this->storage = SplFixedArray::fromArray([$name, $value]);
    }

    public function pivot()
    {
        return [$this[0] => $this[1]];
    }
}