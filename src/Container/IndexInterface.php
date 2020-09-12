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

interface IndexInterface
{
    /**
     * Delete an item from the index
     *
     * @param $index
     * @return mixed
     */
    public function delete($index);

    /**
     * Convert index to an array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Rotate the list
     * Remove an item from the top of the list and place it on the bottom. Or, if retrograde,
     * removes an item from the bottom of the list and places it on top.
     *
     * @param bool $retrograde Rotate backwards?
     * @return IndexInterface
     */
    public function rotate($retrograde = false): IndexInterface;
}