<?php
namespace MainframeTest\Utils\Strings;
namespace MainframeTest\Utils\Strings;

use Mainframe\Utils\Helper\Data;
use Mainframe\Utils\Strings\MString;
use MainframeTest\Utils\MainframeTestCase;

class MStringTest extends MainframeTestCase
{
    public function testMapAppliesCallableToEachCharacter()
    {
        $string = new MString('this is a string');
        $this->assertEquals('th!$ !$ a $tr!ng', (string) $string->map(function($v, $k, $i) {
            $m = ['i' => '!', 's' => '$'];
            return Data::get($m, $v, $v);
        }));
    }

    public function testOffsetMethods()
    {
        $string = new MString('0123456789');
        $this->assertEquals('3', $string->offsetGet(3));
        $this->assertEquals('7', $string->offsetGet(-3));
        $this->assertEquals(null, $string->offsetGet(-13));
        $this->assertTrue($string->offsetExists(0));
        $this->assertTrue($string->offsetExists(9));
        $this->assertTrue($string->offsetExists(-8));
        $this->assertTrue($string->offsetExists(4));
        $this->assertFalse($string->offsetExists(919));
        $this->assertFalse($string->offsetExists(-13));
        $this->assertFalse($string->offsetExists(25));
    }

    public function testArrayAccess()
    {
        $string = new MString(Data::join(range('A', 'z')));
        $this->assertEquals('H', $string[7]);
        $this->assertEquals('t', $string[-7]);
        unset($string[7]);
        $this->assertEquals('H', $string[7]);
    }

    public function testDynamicMethods()
    {
        $string = new MString('lorem ipsum dolor sit amet');
        $this->assertEquals('lemimdliame', (string)$string->filter(fn($l, $k, $i) => in_array($l, range('a', 'n'))));
        $string = new MString('lorem ipsum dolor sit amet');
        $this->assertEquals('or psu oor st t', (string)$string->exclude(fn($l, $k, $i) => in_array($l, range('a', 'n'))));
    }

    public function testReduce()
    {
        $string = new MString('lorem ipsum dolor sit amet');
        $this->assertEquals(13, (string)$string->reduce(fn($a, $l, $k, $i) => $i % 2 ? ++$a : $a), 0);
    }

    public function testAssert()
    {
        $alpha = new MString('abcdef');
        $numeric = new MString('123456');
        $alnum = new MString('abc123');

        $this->assertTrue($alpha->assert(fn($v, $k, $i) => preg_match('/^[a-z]+$/', $v)));
        $this->assertTrue($numeric->assert(fn($v, $k, $i) => preg_match('/^\d+$/', $v)));
        $this->assertTrue($alnum->assert(fn($v, $k, $i) => preg_match('/^[a-z0-9]+$/', $v)));
    }
}
