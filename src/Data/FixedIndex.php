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
use Mainframe\Utils\Exception\OverflowException;
use Mainframe\Utils\Helper\Data;
use SplFixedArray;

/**
 * An index with a fixed size (an index is essentially an array that is numerically indexed).
 */
class FixedIndex extends Index
{
    protected int $size;

    public function __construct(int $size = null, $items = null)
    {
        $itemtot = count($items);
        if (is_null($size)) {

        } else {
            OverflowException::raiseIf(
                $itemtot > $size,
                '%s fixed at %d items. Input has %d items.',
                [ __CLASS__, $size, $itemtot ]
            );
        }
        parent::__construct(Data::toArray($items));
    }

    public static function fromArray($items)
    {
        $items = Data::toArray($items);
        $size = count($items);
        return new static ( $size, $items );
    }
}