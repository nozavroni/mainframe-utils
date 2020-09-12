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

class IsTruthy extends Rule
{
    protected bool $allowWords;

    /**
     * IsTruthy constructor.
     * @param bool $allowWords
     */
    public function __construct(bool $allowWords = false)
    {
        $this->allowWords = $allowWords;
    }


    public function validate(Value $value): bool
    {
        return truthy($value(), $this->allowWords);
    }
}