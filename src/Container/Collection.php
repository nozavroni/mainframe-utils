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

use ArrayIterator;
use Exception;
use Mainframe\Utils\Helper\Data;
use Traversable;

class Collection implements CollectionInterface
{
    use Traits\ArrayAccessors,
        Traits\Accessors,
        Traits\Countable,
        Traits\HigherOrder,
        Traits\Stackable,
        Traits\Sequence,
        Traits\Randomable,
        Traits\Sortable;

    public function __construct(?iterable $items = null)
    {
        $this->storage = [];
        if (!empty($items)) {
            foreach ($items as $key => $val) {
                $this->set($key, $val);
            }
        }
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @throws Exception on failure.
     */
    public function getIterator()
    {
        return new ArrayIterator($this->storage);
    }

    public static function create($items = null): CollectionInterface
    {
        return new static(Data::toArray($items));
    }
}