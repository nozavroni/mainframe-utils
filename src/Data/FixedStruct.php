<?php /** @noinspection PhpMissingParentConstructorInspection */

/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data;

use Mainframe\Utils\Exception\LengthException;
use Mainframe\Utils\Helper\Data;
use SplFixedArray;

/**
 * A container with a fixed size
 */
class FixedStruct extends Struct
{
    /**
     * Struct constructor.
     * @param $input
     */
    public function __construct($input, ?int $size = null)
    {
        if ($size > count($input)) {
            LengthException::raise(
                'Too many items for fixed size data structure'
            );
        }
        $this->storage = SplFixedArray::fromArray(Data::toArray($input));
        if (!is_null($size)) {
            $this->storage->setSize($size);
        }
    }

    public function count()
    {
        return $this->storage->getSize();
    }
}