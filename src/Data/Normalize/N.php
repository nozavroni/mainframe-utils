<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Normalize;

use Mainframe\Utils\Data\Normalize\Filter\FilterInterface;
use Mainframe\Utils\Helper\Str;

class N
{
    const FILTER_NAMESPACE = 'Mainframe\\Utils\\Data\\Normalize\\Filter';

    public static function filter($name, ?array $arguments = null): FilterInterface
    {
        $class = Str::template(
            '{%namespace}\\{%name}',
            [
                'namespace' => static::FILTER_NAMESPACE,
                'name' => $name,
            ]
        );
        return new $class(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return static::filter($name, $arguments);
    }
}