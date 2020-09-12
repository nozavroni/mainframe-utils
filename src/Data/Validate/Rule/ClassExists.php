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

class ClassExists extends Rule
{
    const ALLOW_INTERFACES = 2;

    const ALLOW_TRAITS = 4;

    protected bool $autoload;

    protected int $flags;

    /**
     * ClassExists constructor.
     * @param bool $autoload Should we attempt to autoload the class?
     * @param int $flags Allow interfaces and/or traits?
     */
    public function __construct(bool $autoload = true, int $flags = 0)
    {
        $this->autoload = $autoload;
        $this->flags = $flags;
    }


    public function validate(Value $value): bool
    {
        if (class_exists($value(), $this->autoload)) {
            return true;
        }
        if ($this->flags & static::ALLOW_INTERFACES) {
            if (interface_exists($value(), $this->autoload)) {
                return true;
            }
        }
        if ($this->flags & static::ALLOW_TRAITS) {
            if (trait_exists($value(), $this->autoload)) {
                return true;
            }
        }
        return false;
    }
}