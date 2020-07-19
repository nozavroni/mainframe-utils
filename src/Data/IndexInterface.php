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

use ArrayAccess;

/**
 * An index is essentially a standard array or a list. It is a numerically indexed array object. That's it.
 */
interface IndexInterface extends SequenceInterface, StackableInterface, ArrayAccess
{
    /**
     * Add/insert a new value at the specified index
     *
     * Insert the value newval at the specified index, shuffling the previous value at that index
     * (and all subsequent values) up through the list.
     *
     * @param int $index The numeric offset
     * @param mixed $newval The new value
     * @return mixed
     */
    public function add ( $index , $newval );

    /**
     * Delete item at given offset and re-index all subsequent items
     *
     * @param int $index The numeric offset
     * @return mixed
     */
    public function delete ( $index );
}