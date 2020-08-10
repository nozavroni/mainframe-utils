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

use Mainframe\Utils\Data\Traits\Accessors;
use Mainframe\Utils\Helper\Data;

interface AccessorsInterface
{
    public function isEmpty(): bool;

    /**
     * Get values as an array
     *
     * @return array
     */
    public function values(): array;

    /**
     * Get keys as an array
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Return an array of key/value pairs
     *
     * @return array
     */
    public function pairs(): array;
    /**
     * Get a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @param mixed $default If key is not found, this value is returned
     * @return mixed
     */
    public function get($key = null, $default = null);

    /**
     * Set a value by key / dot notation path
     *
     * @param mixed $value The value to set it to
     * @return $this
     */
    public function set($key = null, $value = null, $overwrite = true): self;

    /**
     * Check if there is a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @return bool
     */
    public function has($key): bool;

    /**
     * Check if there is a value by key or dot-notation path
     *
     * @param mixed $key The key or dot-notation path
     * @return $this
     */
    public function delete($key = null): self;

    /**
     * pull a key from container (delete it) and return it
     * @param $key
     */
    public function pull($key = null);

    public function clear(): self;

    public function toArray(): array;
}