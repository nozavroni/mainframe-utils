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

use Mainframe\Utils\Helper\Data;
use SplDoublyLinkedList;

/**
 * @does it make sense to extend SplDoublyLinkedList rather than using it internally?
 */
class Index extends SplDoublyLinkedList implements IndexInterface
{
    use Traits\Sequence;

    /**
     * Index constructor
     * @param iterable|null $items
     */
    public function __construct($items = null)
    {
        $this->append(Data::toArray($items));
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

}