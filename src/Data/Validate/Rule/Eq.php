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

class Eq extends Rule
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

    /**
     * @param Value $value The value to compare against the input
     * @return bool
     */
    public function validate(Value $value): bool
    {
        return $this->other == $value();
    }
}