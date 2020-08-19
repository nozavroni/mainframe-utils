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
use function Mainframe\Utils\str;

class MinLengthRule extends Rule
{
    /** @var int Minimum length */
    protected int $minLength;

    /** @var bool Should it be inclusive of the length */
    protected bool $inclusive;

    /**
     * MinLengthRule constructor.
     * @param int $minLength
     */
    public function __construct(int $minLength, $inclusive = true)
    {
        $this->minLength = $minLength;
        $this->inclusive = $inclusive;
    }

    public function validate(Value $value): bool
    {
        return str($value)->isMinLength($this->minLength, $this->inclusive);
    }
}