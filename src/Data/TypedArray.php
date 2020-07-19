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

use Mainframe\Utils\Data\Exception\InvalidStructureException;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Exception\LengthException;
use Mainframe\Utils\Helper\Data;
use SplFixedArray;

/**
 * Essentially, in nearly any other language, an array would already be "typed". But since
 */
class TypedArray extends Index
{
    const SCALARS = ['string','integer','boolean','float'];

    protected string $type;

    public static function create(string $type): TypedArray
    {
        InvalidArgumentException::raiseUnless (
            class_exists($type) || in_array($type, static::SCALARS),
            'Invalid type "%s" for TypedArray. Must be a class/interface or a scalar ("%s").',
            [$type, implode('","', static::SCALARS)]
        );
        return new static(null, $type);
    }

    /**
     * Struct constructor.
     * @param iterable $input
     * @param string|null $type
     */public function __construct($input, ?string $type = null)
    {
        parent::__construct($input);

        if ($type === null) {
            $type = typeof(Data::getByPos($input, 1));
        }

        $this->type = $type;
        $typeAssert = Data::assert($input, function($val, $key, $iter) {
            if (is_object($val)) {
                if (!($val instanceof $this->type)) {
                    return false;
                }
            } else {
                if (typeof($val) !== $this->type) {
                    return false;
                }
            }
            return true;
        });

        InvalidStructureException::raiseUnless (
            $typeAssert,
            'Invalid input - all items must be of type "%s"',
            [$this->type]
        );
    }

    public function getType(): string
    {
        return $this->type;
    }
}