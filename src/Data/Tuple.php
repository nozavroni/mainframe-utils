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

use Mainframe\Utils\Data\Exception\ImmutableException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An immutable fixed container with only numeric indexes
 */
class Tuple extends FixedStruct
{
    /**
     * Set a value by key / dot notation path
     *
     * @param mixed $value The value to set it to
     * @return $this
     */
    public function set($key = null, $value = null, $overwrite = true)
    {
        ImmutableException::raise();
    }

    /**
     * Check if there is a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @return $this
     */
    public function delete($key = null)
    {
        ImmutableException::raise();
    }

    /**
     * pull a key from container (delete it) and return it
     * @param $key
     */
    public function pull($key = null)
    {
        ImmutableException::raise('Cannot pull an item from immutable data -- use "get" instead');
    }

    public function clear()
    {
        ImmutableException::raise();
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        ImmutableException::raise();
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        ImmutableException::raise();
    }
}