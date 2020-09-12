<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Container\Traits;

use Mainframe\Utils\Exception\BadMethodCallException;

trait Countable
{
    protected $storage;

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        if ($this->storage instanceof \Countable) {
            return count($this->storage);
        }
        $alts = ['size','getsize','count','length'];
        foreach ($alts as $alt) {
            if (method_exists($this->storage, $alt)) {
                return $this->storage->$alt();
            }
        }
        BadMethodCallException::raise('Unable to determine count');
    }
}