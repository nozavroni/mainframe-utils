<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Traits;

use Mainframe\Utils\Data\StructInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Exception\InvalidArgumentException;

trait StructDecorator
{

    //    /** @var ContainerInterface The container to decorate */
    //    protected ContainerInterface $data;

    /**
     * Get the internal data storage
     *
     * This is essentially just to avoid having to define an $items array on all my traits. This allows
     * me the flexibility to definte how I want my data stored in the class rather than the trait.
     *
     * @return mixed
     */
    abstract protected function getStorage();

    /**
     * StructDecorator constructor.
     * @param StructInterface $data
     * @param mixed ...$more
     */
    public function __construct(StructInterface $data, ...$more)
    {
        $this->data = $data;
        foreach ($more as $arg) {
            if ($arg instanceof StructInterface) {
                $this->mergeInto($arg);
            } else {
                InvalidArgumentException::raise('Not a valid argument for %s: %s', [get_class($this), typeof($arg)]);
            }
        }
    }

    /**
     * @param StructInterface $data
     * @return $this
     */
    public function mergeInto(StructInterface $data)
    {
        foreach ($data as $key => $val) {
            $this->data->set($key, $val);
        }
        return $this;
    }

    /**
     * Any time a method is requested that doesn't exist, we attempt to forward it to the container.
     * If that doesn't work we throw an exception.
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $obj = $this;
        if (!method_exists($this, $method)) {
            if (!method_exists($this->data, $method)) {
                BadMethodCallException::raise('Unknown method for %s: %s',  [get_class($this) ?? 'class', $method]);
            }
            $obj = $this->data;
        }
        return call_user_func_array([$obj, $method], $args);
    }
}