<?php
namespace Mainframe\Utils\Data;

use ArrayAccess;
use ArrayObject;
use Countable;
use Exception;
use IteratorAggregate;
use Mainframe\Utils\Helper\Data;
use Serializable;
use Traversable;

/**
 * A container is essentially just the base class for all the other data structures
 */
class
    Struct
implements
    AccessorsInterface,
    StackableInterface,
    StructInterface,
    ArrayAccess,
    Countable,
    IteratorAggregate,
    Serializable
{

    use Traits\Accessors,
        Traits\ArrayAccessors,
        Traits\Stackable,
        Traits\Countable;

    protected $storage;

    /**
     * Struct constructor.
     * @param $input
     */
    public function __construct($input)
    {
        $this->storage = new ArrayObject(Data::toArray($input));
    }

    /**
     * Get the internal data storage
     *
     * This is essentially just to avoid having to define an $items array on all my traits. This allows
     * me the flexibility to definte how I want my data stored in the class rather than the trait.
     *
     * @return mixed
     */
    protected function getStorage()
    {
        return $this->storage;
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
        $gen = function ($storage) {
            foreach ($storage as $key => $val) {
                yield $key => $val;
            }
        };
        return $gen($this->storage);
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->storage);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->storage = unserialize($serialized);
    }

}