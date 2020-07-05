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
use TypeError;

trait Suppressable
{
    public static function suppress(callable $func, $handler = null)
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
                // that was thrown. In this case, just suppress the type error
            }

            if (!($throwable instanceof static)) {
                // if throwable is not an instance of this exception type, rethrow it
                throw $throwable;
            }
        }
    }
}
