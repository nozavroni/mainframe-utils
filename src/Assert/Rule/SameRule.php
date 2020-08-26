<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Rule;

use Mainframe\Utils\Assert\Value;

class SameRule extends Rule
{
    /** @var mixed The other value to compare against */
    protected $other;

    /**
     * SameRule constructor.
     * @param mixed $other The other value to compare against
     */
    public function __construct($other)
    {
        $this->other = $other;
    }

    public function validate(Value $value): bool
    {
        return $this->other === $value();
    }
}