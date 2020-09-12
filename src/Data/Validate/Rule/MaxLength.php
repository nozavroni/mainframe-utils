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

class MaxLength extends Rule
{
    /** @var int Maximum length */
    protected int $maxLength;

    /** @var bool Should it be inclusive of the length */
    protected bool $inclusive;

    /**
     * MaxLength constructor.
     * @param int $minLength
     */
    public function __construct(int $maxLength, $inclusive = true)
    {
        $this->maxLength = $maxLength;
        $this->inclusive = $inclusive;
    }

    public function validate(Value $value): bool
    {
        return str($value)->isMaxLength($this->maxLength, $this->inclusive);
    }
}