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

class IsFloat extends Rule
{
    protected bool $allowThousandSep;

    /**
     * IsFloat constructor.
     * @param bool $allowThousandSep
     */
    public function __construct(bool $allowThousandSep = false)
    {
        $this->allowThousandSep = $allowThousandSep;
    }

    public function validate(Value $value): bool
    {
        $flags = $this->allowThousandSep ? FILTER_FLAG_ALLOW_THOUSAND : null;
        return (bool) filter_var($value(), FILTER_VALIDATE_FLOAT, $flags);
    }
}