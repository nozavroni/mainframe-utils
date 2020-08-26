<?php
/**
 * Mainframe - a domain codification framework
 *
 * A clumsy attempt to put a name to a concept I've been kicking around for quite some time now. See the
 * README file for a more in-depth overview of this concept and how this library relates to it.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

use Mainframe\Action\Exception\BreakException;
use Mainframe\Action\Exception\FailedAttemptException;
use Mainframe\Utils\Data\Collection;
use Mainframe\Utils\Data\CollectionInterface;
use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Streams\LazyStream;
use Mainframe\Utils\Exception\RuntimeException;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Global Utility Functions
 * Be very cautious about what functions you add to this file. I would like to keep it as lean as possible and most of
 * the time, whatever you're wanting to put here actually already has a home that makes more sense. Functions that
 * belong here are those that are used so often as to need global access. Cross-cutting concerns, in other words. Also,
 * try to maintain the organization of functions by listing them in groups, denoted by comment blocks (see below).
 *
 * @todo If any of these are defined, they just quietly defer to the existing function. This could result in some
 *       extremely difficult bugs to track down. Instead. they should check if the function exists, and if it does, make
 *       a log entry with a WARNING or possibly even an ERROR saying that function was unable to be created.
 *
 * @todo Create a fopen() function that automatically closes its own resource. The way this would work is, instead of
 *       calling fopen() when you want to open a file, you would use my function "file_open" or something. It would
 *       return an object that returns a resource when called as a function. And then its destructor would close the
 *       file handle. You could even have it be a lazy-open type deal where it doesn't actually open the file until
 *       you attempt to use it.
 */

/**
 * //--[ Date & Time Functions ]--//
 */

if (!function_exists('msleep')) {

    function msleep(int $milliseconds)
    {
        usleep($milliseconds * 1000);
    }
}

/**
 * //--[ Callables and Fluid Interface Functions ]--//
 */

if (!function_exists('complement')) {
    /**
     * Returns any callable's "complement" - a callable that always returns the opposite, given the same args
     * This is another somewhat trendy PHP function I've seen around that can be pretty useful.
     *
     * @param callable $f The callable to "complement"
     * @return callable
     */
    function complement(callable $func)
    {
        return function (...$args) use ($func) {
            return !value_of($func, ...$args);
        };
    }
}

if (!function_exists('tap')) {
    /**
     * "Tap" a value and return it
     * This function is mainly just a stylistic flair. It allows you to eliminate temporary variables in a lot of cases
     * by allowing method chaining to continue even when a method returns something other than $this. For instance:
     *
     *      $obj->setValue($val)
     *          ->setAnotherThing($other)
     *          ->tap(function ($v) { $v->doSomeLoggingOrSomething('This is neat'); return $v; }, $obj)
     *          ->doSomethingElse();
     *
     * @see https://medium.com/@taylorotwell/tap-tap-tap-1fc6fc1f93a6
     *
     * @param mixed $value The value to "tap"
     * @param callable $callback The callback to "tap" it with
     * @return mixed
     */
    function tap($value, $func)
    {
        value_of($func, $value);
        return $value;
    }
}

if (!function_exists('value_of')) {

    /**
     * Get the value of a variable
     * If it's a callable, invoke it and return the response
     *
     * @param mixed $value The variable to get the value of
     * @return mixed
     */
    function value_of($value, ...$args)
    {
        if (is_callable($value)) {
            return $value(...$args);
        }
        return $value;
    }

}

if (!function_exists('truthy')) {

    /**
     * Returns true if value is truthy
     *
     * @param mixed $value The value to check
     * @param bool $allowWords If set to true, this will return TRUE for "1", "true", "on" and "yes"
     *                         and FALSE for "0", "false", "off", "no", and ""
     * @return bool
     */
    function truthy($value, $allowWords = false): bool
    {
        $value = value_of($value);
        if ($allowWords) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        if ($value) {
            return true;
        }
        return false;
    }

}

if (!function_exists('falsey')) {

    /**
     * Returns true if value is falsey
     *
     * @param mixed $value The value to check
     * @param bool $allowWords If set to true, this will return TRUE for "1", "true", "on" and "yes"
     *                         and FALSE for "0", "false", "off", "no", and ""
     * @return bool
     */
    function falsey($value, $allowWords = false): bool
    {
        return !truthy($value, $allowWords);
    }

}

if (!function_exists('with')) {

    /**
     * Return whatever is passed to it
     * This may seem useless, but it allows us to eliminate some boring boilerplate type code... temporary variables
     * and that sort of thing. It just makes for cleaner looking code.
     *
     * @param mixed|callable $value The value to return
     * @param callable|null $func If a callable, $value will be passed through it before being returned
     * @return mixed
     */
    function with($value, $func = null)
    {
        return is_callable($func) ? value_of($func, value_of($value)) : value_of($value);
    }

}

if (!function_exists('do_until')) {

    /**
     * Execute a function until a given condition is true. Always runs at least once, even if condition
     * is false from the beginning - much like a do {} while ()
     *
     * @param callable $action The callable to retry
     * @param mixed|callable $condition The condition that determines when to stop executing the function
     * @param mixed $default If retry never returns a value, this value will be returned
     * @param int|null $millisecs Time to sleep between tries (in thousandths of a second)
     * @return mixed
     */
    function do_until(callable $action, $condition, $default = null, ?int $millisecs = null)
    {
        $i = 0;
        $last = $default;
        $sleeper = function() use ($millisecs) {
            if (!is_null($millisecs)) {
                msleep($millisecs);
            }
        };

        do {
            try {
                $last = value_of($action);
            } /*catch (ContinueException $continue) {
                // @todo come back to continue later... it's more etricky than
                $last = $continue->getArg('value');
                $sleeper();
                continue;
            }*/ catch (BreakException $break) {
                // no need to sleep if we are breaking out
                break;
            }
            $sleeper();
        } while (value_of($condition, $last, $i++));

            return $last;

        //    while (true) {
        //        try {
        //            if (is_callable($condition)) {
        //                $condition = $condition($i, $last);
        //            }
        //            if ($condition) {
        //                break;
        //            }
        //            $action($i, $last);
        //        } catch (ContinueException $continue) {
                      // @todo figure out how to make the getLevel() method work here... would be neat,
                      //       but not worth wasting time right now...
        //            continue;
        //        } catch (BreakException $break) {
        //            break;
        //        }
        //    }
    }

}

if (!function_exists('do_if')) {

    /**
     * Do a thing if a condition
     *
     * @param callable $action The action to do
     * @param callable|bool $condition The condition to check
     */
    function do_if(callable $action, $condition)
    {
        if (value_of($condition)) {
            value_of($action);
        }
    }

}

if (!function_exists('do_ifenv')) {

    /**
     * Do a thing if a condition
     *
     * @param callable $action The action to do
     * @param callable|bool $condition The condition to check
     */
    function do_ifenv(callable $action, $envs)
    {
        if (!is_array($envs)) {
            $envs = [$envs];
        }
        $actualEnv = isset($_ENV['APP_ENV']) ? strtolower($_ENV['APP_ENV']) : 'prod';
        $envs = array_map(fn($e) => trim(strtolower($e)), $envs);

        do_if(
            $action,
            in_array($actualEnv, $envs)
        );
    }

}

if (!function_exists('retry')) {

    /**
     * Retry a function a certain number of times or until no exception is thrown.
     *
     * @param callable $func The callable to retry
     * @param int $retries The maximum amount of total tries before it steps retrying
     * @param int|null $millisecs Time to wait between tries (in thousandths of a second)
     * @todo finish this later it's boring the shit out of me
     * @return bool
     */
    function retry(callable $func, $retries = 3, ?int $millisecs = null)
    {
        $success = false;
        $attempts = 0;
        while ($attempts < $retries) {
            try {
                value_of($func);
                $success = true;
            } catch (FailedAttemptException $exception) {
                // do nothing... we just want to use this to determine when things fail
                // other kinds of exceptions should be handled by some other level
            } finally {
                if ($success) {
                    return $success;
                }
                $attempts++;
            }
            if (!is_null($millisecs)) {
                msleep($millisecs);
            }
        }
        return $success;
    }

}

if (!function_exists('chain')) {

    /**
     * @param iterable $chain
     * @param $value
     * @param mixed ...$args
     * @return mixed
     */
    function chain(iterable $chain, $value, ...$args)
    {
        foreach ($chain as $link) {
            $value = value_of($link, $value, ...$args);
        }
        return $value;
    }

}

if (!function_exists('callchain')) {

    /**
     * @param callable[] $chain
     * @param $value
     * @param mixed ...$args
     * @return Closure
     */
    function callchain(iterable $chain, $value, ...$args)
    {
        return function ($value, ...$args) use ($chain) {
            foreach ($chain as $link) {
                $value = value_of($link, $value, ...$args);
            }
            return $value;
        };
    }

}

if (!function_exists('combine')) {

    function combine(iterable $callables): callable
    {
        return function ($value, ...$args) use ($callables) {
            foreach ($callables as $link) {
                value_of($link, $value, ...$args);
            }
        };
    }

}

if (!function_exists('invoke_all')) {

    function invoke_all(iterable $callables, $value, ...$args): void
    {
        $func = combine($callables);
        value_of($func, $value, ...$args);
    }

}

/**
 * //--[ Logging and Debugging ]--//
 */

if (!function_exists('edump')) {

    /**
     * Dump a value if in specified environment(s)
     *
     * @param mixed $value The value to dump
     * @param array|string $envs One or more envs to dump for
     */
    function edump($value, $envs)
    {
        do_ifenv(
            function() use ($value) { dump($value); },
            $envs
        );
    }

}

/**
 * //--[ Exceptions & Error Handling ]--//
 */

if (!function_exists('throw_if')) {

    /**
     * @param $condition
     * @param Throwable $throwable
     * @throws Throwable
     */
    function throw_if($condition, Throwable $throwable)
    {
        if (value_of($condition)) {
            throw $throwable;
        }
    }

}

if (!function_exists('throw_unless')) {

    /**
     * @param $condition
     * @param Throwable $throwable
     * @throws Throwable
     */
    function throw_unless($condition, Throwable $throwable)
    {
        if (!value_of($condition)) {
            throw $throwable;
        }
    }

}

if (!function_exists('recover')) {

    /**
     * Call a callable and catch/recover from any thrown exception
     * If a default argument is supplied, it will be returned when an exception is thrown. If the default is a callable
     * and it is type-hinted to an exception that doesn't match the one that was thrown, the original exception is
     * rethrown as if this function were never called. Also, if you'd like to quietly handle the exception in the back-
     * ground, you can pass in a third callable that does so. It can also be type-hinted, except if its type hint
     * doesn't match the thrown exception, its callable simply isn't used.
     *
     * @param callable $func
     * @param callable|mixed|null $default
     * @param callable|null $handler
     * @throws \Throwable
     * @return mixed|null
     */
    function recover($func, $default = null, $handler = null)
    {
        $throwable = null;
        try {
            return value_of($func);
        } catch (Exception $throwable) {
            // @note The reason I used Exception here rather than "Throwable" is because I don't want unintentional
            //       errors in the code of $func to trip the default -- only exceptions.
            try {
                value_of($handler, $throwable);
            } catch (TypeError $hterror) {
                // this means that the handler was type-hinted to an incompatible throwable to the one
                // that was thrown. In this case, just swallow the type error
            }
        }
        try {
            return value_of($default, $throwable);
        } catch (TypeError $error) {
            // this is thrown if the default callable type-hints an incompatible exception
            // in this case, we just want to rethrow the original exception
            if ($throwable instanceof Throwable) {
                throw $throwable;
            }
        }
    }

}

if (!function_exists('suppress')) {

    /**
     * Passively run some code and suppress any exceptions that might be thrown.
     *
     * @param callable $func A callback to execute where all exceptions are muffled (ignored)
     */
    function suppress(callable $func)
    {
        try {
            value_of($func);
        } catch (Exception $exception) {
            // muffled
        }
    }

}

/**
 * //--[ Arrays, Data Structures & Dot Notation ]--//
 */

if (!function_exists('collect')) {

    /**
     * Create a collection from items
     *
     * @param array|mixed $items Items to convert to a factory
     * @return Collection
     */
    function collect($items = null): CollectionInterface
    {
        return Collection::create($items);
    }

}

if (!function_exists('to_array')) {

    /**
     * Convert any value to an array
     * If the second argument is false, an exception will be thrown when this function is unable to convert the input
     * into an array in any meaningful way (if the input is a file resource, for instance). If it is true, the input
     * will be forced into an array no matter what, resulting in sometimes unexpected behavior.
     *
     * @param mixed $items The value to convert to an array
     * @param bool $force Always return an array no matter what
     * @return array
     * @throws InvalidArgumentException
     */
    function to_array($items, $force = false): array
    {
        return Data::toArray($items, $force);
    }

}

if (!function_exists('to_stream')) {

    /**
     * Convert (almost) any value to a stream resource
     *
     * @param mixed $data The data to convert to stream
     * @return resource
     */
    function to_stream($data, array $options = [])
    {
        return stream_for($data, $options);
    }

}

if (!function_exists('typeof')) {

    /**
     * Get the type of a given value
     * Works exactly like gettype except that if value is an object, its class is returned.
     *
     * @param mixed $value The value to get the type of
     * @param bool $typeOnly If true, return only data type, excluding the array count or the type of data in the set
     * @return string
     */
    function typeof($value, $typeOnly = false)
    {
        $type = gettype($value);
        if ($type == 'object') {
            if ($class = get_class($value)) {
                $type = $class;
            }
        }
        if (!$typeOnly) {
            if ($type == 'array' || $value instanceof ArrayObject) {
                $arr = $value;
                if (!is_array($arr)) {
                    $arr = iterator_to_array($arr);
                }
                if (empty($arr)) {
                    $type .= '(0)';
                } else {
                    // if all of its elements are of the same type, include it
                    $alltypes = array_map('typeof', $arr);
                    $uniquetypes = array_unique($alltypes);
                    if (count($uniquetypes) === 1) {
                        $type = sprintf('%s[%s]', $type, current($alltypes));
                    }
                    // if array or array object, include its count
                    $type = sprintf('%s(%d)', $type, count($value));
                }
            }
        }
        return $type;
    }

}

if (!function_exists('absolute_offset_length')) {

    /**
     * Given an array or countable, an offset (can be negative or null), and a length (also can be negative
     * or null), and returns a two-item array containing the absolute offset and length.
     *
     * @param $items
     * @param int $offset
     * @param int|null $length
     */
    function absolute_offset_length($items, int $offset = null, ?int $length = null)
    {
        $count = Data::count($items);

        if (is_null($offset)) {
            $startoffset = 0;
        }
        if ($offset < 0) {
            $startoffset = $count - abs($offset);
        }
        if ($offset > $count) {
            return [0, 0];
        }
        if (is_null($length)) {
            $endoffset = $count;
        }
        if ($length < 0) {
            $endoffset = $count - abs($length);
            if ($endoffset < 0) {
                $endoffset = 0;
            }
        }

        if (!isset($startoffset)) {
            $startoffset = ($offset < 0) ? 0 : $offset;
        }

        if (!isset($endoffset)) {
            $endoffset = $startoffset + $length;
        }

        if ($startoffset < 0) {
            $startoffset = 0;
        }
        if ($endoffset > $count) {
            $endoffset = $count;
        }

        if ($startoffset > $endoffset) {
            return [0, 0];
        }

        return [$startoffset, $endoffset];
    }

}

/**
 * //--[ Streams ]--//
 */

if (!function_exists('lazy_fopen')) {

    function lazy_fopen($filename, $mode, $use_include_path = false, $context = null)
    {
        return new LazyStream(...func_get_args());
    }

}

if (!function_exists('safe_fopen')) {
    /**
     * Safely opens a PHP stream resource using a filename.
     *
     * When fopen fails, PHP normally raises a warning. This function adds an
     * error handler that checks for errors and throws an exception instead.
     *
     * @param string $filename File to open
     * @param string $mode     Mode used to open the file
     *
     * @return resource
     * @throws \RuntimeException if the file cannot be opened
     */
    function safe_fopen($filename, $mode, $use_include_path = false, $context = null)
    {
        $ex = null;
        set_error_handler(function () use ($filename, $mode, &$ex) {
            $ex = RuntimeException::create(
                'Unable to open %s using mode %s: %s',
                [$filename, $mode, func_get_args()[1]]
            );
        });

        $handle = fopen($filename, $mode, $use_include_path, $context);
        restore_error_handler();

        if ($ex) {
            /** @var $ex RuntimeException */
            throw $ex;
        }

        return $handle;
    }
}

/**
 * //--[ Bitwise Operations ]--//
 */

if (!function_exists('flag_set')) {

    function flag_set(int &$flag, int $option): int
    {
        return ($flag |= $option);
    }

}

if (!function_exists('flag_unset')) {

    function flag_unset(int &$flag, int $option): int
    {
        return ($flag &= ~$option);
    }

}

if (!function_exists('flag_toggle')) {

    function flag_toggle(int &$flag, int $option): int
    {
        return ($flag ^= $option);
    }

}

if (!function_exists('flag_assert')) {

    function flag_assert(int $flag, int $option): bool
    {
        return ($flag & $option) > 0;
    }

}