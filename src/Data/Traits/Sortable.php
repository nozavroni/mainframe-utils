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

use Mainframe\Utils\Helper\Data;

trait Sortable
{
    /**
     * Shuffle (randomize) the order of this collection's values (in-place)
     *
     * @return Collection
     */
    public function shuffle()
    {
        $new = [];
        $keys = array_keys($this->items);
        shuffle($keys);
        foreach ($keys as $key) {
            $new[$key] = $this->items[$key];
        }
        $this->items = $new;

        return $this;
    }

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
    public function sort(callable $alg = null)
    {
        if (is_null($alg)) {
            $flag = Data::assert('Noz\is_numeric') ? SORT_NUMERIC : SORT_NATURAL;
            asort($this->items, $flag);
        } else {
            uasort($this->items, $alg);
        }

        return $this;
    }

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
    public function ksort(callable $alg = null)
    {
        if (is_null($alg)) {
            $flag = $this->keys()->assert('Noz\is_numeric') ? SORT_NUMERIC : SORT_NATURAL;
            ksort($this->items, $flag);
        } else {
            uksort($this->items, $alg);
        }

        return $this;
    }
}