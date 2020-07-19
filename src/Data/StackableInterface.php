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

interface StackableInterface
{
    public function top (  );
    public function bottom (  );
    public function push ( $value );
    public function shift (  );
    public function unshift ( $value );
    public function pop (  );
}