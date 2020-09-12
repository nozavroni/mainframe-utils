<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Validate\Rule;

use Mainframe\Utils\Data\Value;
use function Mainframe\Utils\str;

class IsEmpty extends Rule
{
    public function validate(Value $value): bool
    {
        return !empty($value->getValue());
    }
}