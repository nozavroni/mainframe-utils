<?php
/**
 * Mainframe - Utilities - Namespaced Functions
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils;

use Mainframe\Utils\Helper\Str;
use Mainframe\Utils\Strings\MString;

/**
 * //--[ String and Text Related Functions ]--//
 */

function str($val): MString
{
    return Str::make($val);
}

