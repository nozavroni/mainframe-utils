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

interface StackableInterface
{
    public function push($item): int;
    public function pop();
    public function unshift($itemn): int;
    public function shift();
    public function peekLeft();
    public function peekRight();
}