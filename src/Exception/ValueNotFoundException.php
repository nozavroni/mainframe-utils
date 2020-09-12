<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Exception;

class ValueNotFoundException extends RuntimeException
{
    public static function getDefaultMessage(): string
    {
        return 'Value could not be found: "{%value}"';
    }

    /**
     * @inheritDoc
     */
    public static function getRequiredArgs(): array
    {
        return ['value'];
    }
}