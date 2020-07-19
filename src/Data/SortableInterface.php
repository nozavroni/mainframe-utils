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

interface SortableInterface
{
    /**
     * Sort the items by a given algorithm or, if no algorithm provided, simply sort them alphanumerically.
     *
     * @param callable|null $algo The callback algorithm to use for sorting
     * @return SortableInterface
     */
    public function sort($algo = null): SortableInterface;

    /**
     * Reverse sort the items by a given algorithm or, if no algorithm provided, simply sort them alphanumerically.
     *
     * @param callable|null $algo The callback algorithm to use for sorting
     * @return SortableInterface
     */
    public function rsort($algo = null): SortableInterface;

    /**
     * Sort the items by key using the given algorithm or, if no algorithm provided, simply sort them alphanumerically.
     *
     * @param callable|null $algo The callback algorithm to use for sorting
     * @return SortableInterface
     */
    public function ksort($algo = null): SortableInterface;

    /**
     * Reverse sort the items by key using the given algorithm or, if no algorithm provided, simply sort them alphanumerically.
     *
     * @param callable|null $algo The callback algorithm to use for sorting
     * @return SortableInterface
     */
    public function rksort($algo = null): SortableInterface;
}