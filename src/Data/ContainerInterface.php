<?php
namespace Mainframe\Utils\Data;

interface ContainerInterface
{
    /**
     * Get an item by name (dot-notation supported)
     *
     * @param string|null $key The name of the item to get
     * @param mixed $default The default to return if item doesn't exist
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set an item's value (dot-notation support)
     *
     * @param string $key The name of the item to set a value for
     * @param mixed $value The value to set the item to
     * @param bool $overwrite Whether to overwrite existing keys
     * @return mixed
     */
    public function set($key, $value, $overwrite = true);

    /**
     * Does an item exist?
     *
     * @param string $key The name to check for
     * @return bool
     */
    public function has($key);

    /**
     * Delete an item by name
     *
     * @param string $key The item to delete
     * @return $this
     */
    public function delete($key);

    /**
     * Clear all items from the container
     *
     * @return $this
     */
    public function clear();

    /**
     * Convert the container to an array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Is the container empty?
     *
     * @return bool
     */
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
}