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

interface RandomableInterface
{
    public function shuffle();

    public function randomize(): RandomableInterface;

    /**
     * Pick and return one item at random
     */
    public function random();

    /**
     * Pick a random key/offset and return it
     */
    public function randomKey();
}