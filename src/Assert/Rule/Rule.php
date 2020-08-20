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

use function Mainframe\Utils\str;

abstract class Rule implements RuleInterface
{
    public function getName(): string
    {
        return str(static::class)->afterLast('\\');
    }

    public function getDescription(): string
    {
        return '';
    }
}