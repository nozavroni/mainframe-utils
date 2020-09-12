<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Normalize;

use Mainframe\Utils\Container\PriorityQueue;
use Mainframe\Utils\Data\Normalize\Filter\Queue;
use Mainframe\Utils\Data\Value;
use SplPriorityQueue;

class Chain
{
    protected Queue $chain;

    /**
     * @inheritDocaas
     */
    public function __construct()
    {
        $this->chain = new Queue();
    }

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        $priority = array_pop($arguments) ?? Queue::PRIORITY_NORMAL;
        $this->chain->insert(N::filter($name, $arguments), (int) $priority);
        return $this;
    }

//    /**
//     * @inheritDoc
//     */
//    public static function __callStatic($name, $arguments)
//    {
//        // TODO: Implement __callStatic() method.
//    }

    /**
     * @inheritDoc
     */
    public function __invoke($value)
    {
        if (!($value instanceof Value)) {
            $value = new Value($value);
        }

    }

}