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
use Countable;
use SplDoublyLinkedList;

/**
 * @does it make sense to extend SplDoublyLinkedList rather than using it internally?
 */
class Index extends SplDoublyLinkedList implements IndexInterface, HigherOrderInterface, Countable
{
    use Traits\Sequence,
        Traits\HigherOrder;

    /**
     * Index constructor
     * @param iterable|null $items
     */
    public function __construct($items = null)
    {
        foreach (Data::toArray($items) as $item) {
            $this->push($item);
        }
    }

    public function getIterator()
    {
        return Data::toGenerator($this);
    }

    public static function create($items): self
    {
        return new Index($items);
    }

    /**
     * Delete item at given offset and re-index all subsequent itemsvss
     *
     * @param int $index The numeric offset
     * @return mixed
     */
    public function delete($index)
    {
        $this->offsetUnset($index);
    }

    /**
     * Convert index to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    /**
     * Rotate the list
     * Remove an item from the beginning of the list and place it at the end. Or, if retrograde,
     * removes an item from the end of the list and places it at the beginning.
     *
     * @param bool $retrograde Rotate backwards?
     * @return IndexInterface
     */
    public function rotate($retrograde = false): IndexInterface
    {
        $index = clone $this;
        if ($retrograde) {
            $index->unshift($index->pop());
            return $index;
        }
        $index->push($index->shift());
        return $index;
    }

}