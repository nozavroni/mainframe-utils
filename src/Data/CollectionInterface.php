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

use Countable;
use ArrayAccess;
use IteratorAggregate;

interface
    CollectionInterface
extends
    HigherOrderInterface,
    AccessorsInterface,
    StackableInterface,
    SortableInterface,
    ArrayAccess,
    IteratorAggregate,
    Countable
{
    public static function create($items): CollectionInterface;
}
