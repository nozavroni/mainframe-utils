<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Rules;

use Mainframe\Utils\Helper\Data;
use function Mainframe\Utils\str;

class ContainsRule extends Rule
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    protected function validate($value): bool
    {
        if (is_iterable($value)) {
            return Data::contains($value, $this->item);
        }
        return null !== str($value)->indexOf($this->item);
    }
}