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

interface SortableInterface
{
    /**
     * Sort the collection by value (in-place)
     *
     * Sorts the collection by value using the provided algorithm (which can be either the name of a native php function
     * or a callable).
     *
     * @note The sorting methods are exceptions to the usual callback signature. The callback for this method accepts
     *       the standard arguments for sorting algorithms ( string $str1 , string $str2 ) and should return an integer.
     *
     * @see http://php.net/manual/en/function.strcmp.php
     *
     * @param callable $alg The sorting algorithm (defaults to strcmp)
     *
     * @return self
     */
    public function sort(callable $alg = null): self;

    /**
     * Sort the collection by key (in-place)
     *
     * Sorts the collection by key using the provided algorithm (which can be either the name of a native php function
     * or a callable).
     *
     * @note The sorting methods are exceptions to the usual callback signature. The callback for this method accepts
     *       the standard arguments for sorting algorithms ( string $str1 , string $str2 ) and should return an integer.
     *
     * @see http://php.net/manual/en/function.strcmp.php
     *
     * @param callable $alg The sorting algorithm (defaults to strcmp)
     *
     * @return self
     */
    public function ksort(callable $alg = null): self;
}