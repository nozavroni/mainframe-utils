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

use Mainframe\Utils\Data\ContainerInterface;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Exception\InvalidArgumentException;

trait DataDecorator
{
    /** @var ContainerInterface The container to decorate */
    protected ContainerInterface $data;

    /**
     * DataDecorator constructor.
     * @param ContainerInterface $data
     * @param mixed ...$more
     */
    public function __construct(ContainerInterface $data, ...$more)
    {
        $this->data = $data;
        foreach ($more as $arg) {
            if ($arg instanceof ContainerInterface) {
                $this->mergeInto($arg);
            } else {
                InvalidArgumentException::raise('Not a valid argument for %s: %s', [get_class($this), typeof($arg)]);
            }
        }
    }

    /**
     * @param ContainerInterface $data
     * @return $this
     */
    public function mergeInto(ContainerInterface $data)
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