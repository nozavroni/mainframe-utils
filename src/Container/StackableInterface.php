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

interface StackableInterface
{
    public function pop();

    public function unshift($item): int;

    public function push($item): int;

    public function shift();

    public function top();

    public function bottom();
}