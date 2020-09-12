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

use Exception;
use Throwable;
use TypeError;

trait Recoverable
{
    public static function recover(callable $func, $default = null, $handler = null)
    {
        $throwable = null;
        try {
            return value_of($func);
        } catch (Exception $throwable) {
            // @note The reason I used Exception here rather than "Throwable" is because I don't want unintentional
            //       errors in the code of $func to trip the default -- only exceptions.
            try {
                value_of($handler, $throwable);
            } catch (TypeError $error) {
                // this means that the handler was type-hinted to an incompatible throwable to the one
                // that was thrown. In this case, just swallow the type error
            }

            if (!($throwable instanceof static)) {
                // if throwable is not an instance of this exception type, rethrow it
                throw $throwable;
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

            if (!($throwable instanceof static)) {
                // if throwable is not an instance of this exception type, rethrow it
                throw $throwable;
            }
        }
    }
}
