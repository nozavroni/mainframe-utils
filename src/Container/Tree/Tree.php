<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Container\Tree;

use Mainframe\Utils\Container\TypedArray;

class Tree
{
    protected TypedArray $data;

    public function __construct()
    {
        $this->data = TypedArray::create(NodeInterface::class);
    }
}