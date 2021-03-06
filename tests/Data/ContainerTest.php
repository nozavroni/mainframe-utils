<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils\Data;

use Mainframe\Utils\Data\Exception\InvalidStructureException;
use Mainframe\Utils\Data\Struct;
use Mainframe\Utils\Data\TypedArray;
use Mainframe\Utils\Helper\Data;
use MainframeTest\Utils\MainframeTestCase;

class ContainerTest extends MainframeTestCase
{
//    public function testCreateContainerInitStorage()
//    {
//        $a = Data::getKeyByPos($this->samples['users'], 3);
//        $this->assertEquals('6ZD869R-3', $a);
//        $data = new Struct($arr = [
//            'user' => Data::getByPos($this->samples['users'], -4),
//            'nums' => range(0, 100),
//            'chars' => range(' ', '}'),
//        ]);
//        $this->assertEquals(
//            '',
//            Data::getByPos($data, 3)
//        );
//    }

    public function testTypedStructAllowsOnlyItemsOfOneType()
    {
        $val = InvalidStructureException::recover(function() {
            $a = new TypedArray([1,2,3,4,5,56,0, 'e']);
            return $a;
        }, 'none');

        $this->assertEquals('none', $val, 'Testing to ensure that default was returned (meaning an exception was suppressed)');

        $val = InvalidStructureException::recover(function() {
            $a = TypedArray::create('string');
            $a->push(4);
            return $a;
        }, 'none');

        $this->assertEquals('none', $val, 'Testing to ensure that default was returned (meaning an exception was suppressed)');

    }
}