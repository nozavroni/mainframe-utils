<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data;

interface HigherOrderInterface
{
    public function assert(?callable $func = null, $expected = true): bool;

    public function any(?callable $func = null, $expected = true): bool;

    public function contains($value, $key = null): bool;

    public function filter(?callable $func = null): CollectionInterface;

    public function exclude(?callable $func = null): CollectionInterface;

    public function each(callable $func): self;

    public function first(?callable $func = null, $default = null);

    public function last(?callable $func = null, $default = null);

    public function reduce(callable $func, $initial = null);

    public function map(callable $func): CollectionInterface;

    public function partition(?callable $func): array;

    public function pipe(callable $func);
}