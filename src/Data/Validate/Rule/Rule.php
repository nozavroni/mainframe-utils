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

abstract class Rule implements RuleInterface
{
    /**
     * Get a human-friendly name for this rule
     *
     * @return string
     */
    public function getName(): string
    {
        return str(static::class)->afterLast('\\');
    }

    /**
     * Get a human-friendly description for this rule
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    public function isValid($value): bool
    {
        if (!($value instanceof Value)) {
            $value = new Value($value);
        }
        return $this->validate($value);
    }
}