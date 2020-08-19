<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert;

/**
 * Value object
 * This class/object is used as a stand-in for a value until such a time as the actual value is available.
 * You instantiate it
 */
class Value
{
    /** @var mixed|callable The value (or callback) to run assertions on */
    protected $value;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Invoke the object as if it were a function
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getValue();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value
     *
     * @return mixed
     */
    public function getValue()
    {
        return value_of($this->value);
    }
//
//    public function or(...$choices)
//    {
//        foreach ($choices as $choice) {
//            if (value_of($choice, $this)) {
//                return true;
//            }
//        }
//        return false;
//    }
//
//    public function xor($first, $second)
//    {
//        return value_of($first, $this) xor value_of($second, $this);
//    }
//
//    public function and(...$choices)
//    {
//        foreach ($choices as $choice) {
//            if (!value_of($choice, $this)) {
//                return false;
//            }
//        }
//        return true;
//    }
//
//    public function if($condition, $then, $else = null)
//    {
//        if (value_of($condition, $this)) {
//            return value_of($then, $this);
//        } else {
//            return value_of($else, $this);
//        }
//    }
//
//    public function unless($condition, $action)
//    {
//        if (!value_of($condition, $this)) {
//            return value_of($action, $this);
//        }
//        return false;
//    }
//
//    public function assert($condition)
//    {
//        return value_of($condition, $this);
//    }

}