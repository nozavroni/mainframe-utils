<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Strings;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use Mainframe\Utils\Exception\BadMethodCallException;
use Mainframe\Utils\Exception\OutOfBoundsException;
use Mainframe\Utils\Exception\OutOfRangeException;
use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Helper\Str;
use Symfony\Component\String\UnicodeString;
use Traversable;
use function Mainframe\Utils\str;

/**
 * @method MString filter(callable $func)
 * @method MString exclude(callable $func)
 */
class MString extends UnicodeString implements ArrayAccess, Countable, IteratorAggregate
{
    const CALL_METHODS = ['filter', 'exclude'];

    /**
     * Create a new string object
     *
     * @param mixed|callable $val The value to create a string from
     * @return MString
     */
    public static function create($val): MString
    {
        return new MString((string)value_of($val));
    }

    /**
     * Create a slug from this string
     *
     * @param int|null $maxlen If set, resulting string will be truncated to this length
     * @return MString
     */
    public function sluggify(?int $maxlen = null): MString
    {
        return static::create(Str::sluggify((string)$this, $maxlen));
    }

    /**
     * Is string at least X chars in length?
     *
     * @param int $length The minimum length
     * @param bool $inclusive Should comparison be inclusive?
     * @return bool
     */
    public function isMinLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() >= $length :
            $this->length() > $length;
    }

    /**
     * Is string at most X chars in length?
     *
     * @param int $length The maximum length
     * @param bool $inclusive Should comparison be inclusive?
     * @return bool
     */
    public function isMaxLength(int $length, $inclusive = true): bool
    {
        return $inclusive ?
            $this->length() <= $length :
            $this->length() < $length;
    }

    /**
     * Is string exactly X chars in length?
     *
     * @param int $length The length
     * @return bool
     */
    public function isLength(int $length): bool
    {
        return $this->length() == $length;
    }

    /**
     * Convert string to an array of characters
     * Returns an array, each element consisting of a single character string.
     *
     * @return array
     */
    public function toArray(): array
    {
        return Data::map($this->chunk(), fn ($v, $k, $i) => (string) $v);
    }

    /**
     * @param string $chars Characters to intersect with
     * @return MString
     */
    public function intersect(string $chars): MString
    {
        return str(join(array_unique(array_intersect($this->toArray(), str($chars)->toArray()))));
    }

    /**
     * @param string $chars Characters to intersect with
     * @return MString
     */
    public function difference(string $chars): MString
    {
        return str(join(array_unique(array_diff($this->toArray(), str($chars)->toArray()))));
    }

    /**
     * Split the string at a given substring
     *
     * @param string $substr
     * @return array
     */
    public function splitOn(string $substr): array
    {
        return [
            $this->before($substr),
            $this->after($substr),
        ];
    }

    /**
     * Split on the last instance of the given substring
     *
     * @param string $substr
     * @return array
     */
    public function splitOnLast(string $substr): array
    {
        return [
            $this->beforeLast($substr),
            $this->afterLast($substr),
        ];
    }

    /**
     * Return the first character to match the provided predicate
     *
     * @param null $func
     * @param null $default
     * @return string
     */
    public function first($func = null, $default = null)
    {
        return Data::first($this->toArray(), ...func_get_args());
    }

    /**
     * Return the last character to match the provided predicate
     *
     * @param null $func
     * @param null $default
     * @return string
     */
    public function last($func = null, $default = null)
    {
        return Data::last($this->toArray(), ...func_get_args());
    }

    /**
     * Run each character through a functions
     *
     * @param callable $func
     * @return MString
     */
    public function map(callable $func): MString
    {
        return str(Data::join(Data::map($this->toArray(), $func)));
    }

    /**
     * @param callable $func
     * @param null $initial
     * @return mixed|nulle
     */
    public function reduce(callable $func, $initial = null)
    {
        return Data::reduce($this->toArray(), $func, $initial);
    }

    /**
     * @param callable $func
     * @param null $initial
     * @return mixed|null
     */
    public function assert(callable $func, $expected = true, $strict = false): bool
    {
        return Data::assert($this->toArray(), $func, $expected, $strict);
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        try {
            Data::getByOffset($this->toArray(), $offset);
            return true;
        } catch (OutOfBoundsException $e) {
            return false;
        }
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return Data::getByOffset($this->toArray(), $offset);
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->splice($value, $offset, 1);
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->splice('', $offset, 1);
    }

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
        return $this->length();
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @throws Exception on failure.
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments)
    {
        BadMethodCallException::raiseUnless(
            Data::contains(static::CALL_METHODS, strtolower($name)),
            'Bad method call -- no dynamic method called "%s"',
            [$name]
        );

        array_unshift($arguments, $this->toArray());
        $method = \Closure::fromCallable([Data::class, $name]);
        $result = $method(...$arguments);
        return str(Data::join($result));

    }


}