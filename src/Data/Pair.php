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

use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Helper\Data;
use SplFixedArray;

/**
 * Essentially a two-item tuple, usually used to represent a key/value pair
 */
class Pair extends Tuple
{
    public function __construct($key, $value)
    {
        InvalidArgumentException::raiseUnless(is_scalar($key));
        parent::__construct(2, [$key, $value]);
    }

    public function pivot()
    {
        return [$this->getKey() => $this->getValue()];
    }

    public function getKey()
    {
        return $this->offsetGet(0);
    }

    public function getValue()
    {
        return $this->offsetGet(1);
    }
}