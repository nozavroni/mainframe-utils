<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Normalize\Filter;

use function Mainframe\Utils\str;

class ToUpper implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply($value)
    {
        return (string) str($value)->upper();
     }
}