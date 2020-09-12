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

use Throwable;

interface RaisableInterface
{
    public static function getDefaultMessage(): string;

    public static function getRequiredArgs(): array;

    public static function create(?string $str = null, array $args = [], ?Throwable $throwable = null): self;

    public static function raise(?string $str = null, array $args = [], ?Throwable $throwable = null): void;

    public static function raiseIf($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null): void;

    public static function raiseUnless($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null): void;
}