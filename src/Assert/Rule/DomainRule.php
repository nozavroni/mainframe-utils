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

class DomainRule extends Rule
{
    protected bool $isHostname;

    /**
     * DomainRule constructor.
     * @param bool $hostname
     */
    public function __construct(bool $isHostname = true)
    {
        $this->isHostname = $isHostname;
    }

    /**
     * @param Value $value
     * @return bool
     */
    public function validate(Value $value): bool
    {
        $flags = 0;
        if ($this->isHostname) {
            flag_set($flags, FILTER_FLAG_HOSTNAME);
        }
        return (bool) filter_var($value(), FILTER_VALIDATE_DOMAIN, $flags);
    }
}