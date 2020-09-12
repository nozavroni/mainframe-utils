<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Exception\Traits;

use DateTime;
use Mainframe\Utils\Exception\LogicException;
use Mainframe\Utils\Exception\RaisableInterface;
use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Helper\Str;
use Throwable;
use function Mainframe\Utils\str;

const DATE = 'Y-m-d';
const TIME = 'g:i:sa';

trait Raisable
{
    /** @var string Repl format */
    protected static $replFormat = '{%%%s}';

    /**
     * @return array
     */
    public static function getRequiredArgs(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getDefaultMessage(): string
    {
        return 'An error occurred: {%msg}';
    }

    /**
     * Essentially this is just a different way to throw an exception where no message is required, in fact
     * no arguments at all are. And the exception message works as sprintf() does. This just makes it cleaner
     * to do things like InvalidArgumentException::raise('"%s" is not a valid argument');
     *
     * In addition, there a number of pre-defined values that do not need to be specified in $args. Those are:
     *
     *      {%type} - exception type
     *      {%date} - current date
     *      {%time} - current time
     *      {%datetime} - current date/time
     *      {%timestamp} - current date/time timestamp
     *      {%previous} - previous exception type (or none if not one)
     *
     * @param string $str
     * @param array $args
     * @param Throwable|null $throwable
     * @throws RaisableInterface
     */
    public static function raise(?string $str = null, array $args = [], ?Throwable $throwable = null): void
    {
        throw static::create($str, $args, $throwable);
    }

    public static function raiseIf($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null): void
    {
        if (value_of($condition)) {
            static::raise($str, $args, $throwable);
        }
    }

    public static function raiseUnless($condition, ?string $str = null, array $args = [], ?Throwable $throwable = null): void
    {
        if (!value_of($condition)) {
            static::raise($str, $args, $throwable);
        }
    }

    public static function create(?string $str = null, array $args = [], ?Throwable $throwable = null): self
    {
        $str ??= static::getDefaultMessage();
        $required = static::getRequiredArgs();
        $dt = new DateTime;
        $classname = str(self::class)->afterLast('\\');
        $predefined = [
            'type' => self::class,
            'date' => $dt->format(DATE),
            'time' => $dt->format(TIME),
            'microtime' => microtime(true),
            'datetime' => $dt->format(DATE . ' ' . TIME),
            'timestamp' => $dt->getTimestamp(),
            'timezone' => date_default_timezone_get(),
            'class' => get_called_class(),
            'user' => get_current_user(),
            'parent' => get_parent_class(),
            'msg' => 'Unknown problem - ' . $classname
        ];

        $args = $args + $predefined;
        if (!empty($required) && !Data::containsAll(Data::keys($args), $required)) {
            throw new LogicException('Missing some required args for raisable exception');
        }

        return new static (
            vsprintf(
                Str::template($str, $args, static::$replFormat),
                $args,
            // array_filter($args, fn ($key) => is_int($key), ARRAY_FILTER_USE_KEY)
            ),
            0,
            $throwable
        );
    }
}