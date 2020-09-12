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

class IpAddress extends Rule
{
    protected $options = [
        'ipv4' => false, // means only allow v4
        'ipv6' => false, // means only allow v6
        'allowPrivRange' => true,
        'allowResRange' => true,
    ];

    /**
     * IpAddress constructor.
     * @param bool[] $options
     */
    public function __construct(?array $options = null)
    {
        if (!is_null($options)) {
            $this->options = array_replace($this->options, $options);
        }
    }

    public function validate(Value $value): bool
    {
        $flags = 0;
        if ($this->options['ipv4']) {
            flag_set($flags, FILTER_FLAG_IPV4);
        }
        if ($this->options['ipv6']) {
            flag_set($flags, FILTER_FLAG_IPV6);
        }
        if (!$this->options['allowPrivRange']) {
            flag_set($flags, FILTER_FLAG_NO_PRIV_RANGE);
        }
        if (!$this->options['allowResRange']) {
            flag_set($flags, FILTER_FLAG_NO_RES_RANGE);
        }
        return (bool)filter_var($value(), FILTER_VALIDATE_IP, $flags);
    }
}