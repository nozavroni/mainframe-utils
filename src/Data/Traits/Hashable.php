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

use Mainframe\Utils\Data\HashableInterface;

trait Hashable
{
    public function equals(object $obj): bool
    {
        if ($obj instanceof HashableInterface) {
            // @todo not sure if this is right
            return $obj == $this && $obj->hash() === $this->hash();
        }
        return false;
    }

    public function hash(): string
    {
        return spl_object_hash($this);
    }
}