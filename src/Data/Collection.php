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

class Collection implements \ArrayAccess
{
    use Traits\ArrayAccessors,
        Traits\Accessors,
        Traits\Countable,
        Traits\Stackable,
        Traits\Sequence,
        Traits\Randomable,
        Traits\Sortable;

    public function __construct(?iterable $items)
    {
        $this->storage = Data::toArray($items);
    }
}