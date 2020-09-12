<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils\Helper;

use Mainframe\Utils\Exception\ValueNotFoundException;
use Mainframe\Utils\Helper\Data;
use MainframeTest\Utils\MainframeTestCase;

class DataTest extends MainframeTestCase
{
    public function testHas()
    {
        $arr = array_flip(range('a','z'));
        $this->assertTrue(Data::has($arr, 'a'));
        $this->assertTrue(Data::has($arr, 'z'));
        $this->assertFalse(Data::has($arr, 'aa'));
        $this->assertTrue(Data::has($arr, ['a','h','k']));
        $this->assertFalse(Data::has($arr, ['a', 'b', 1, 'c', '239o']));
    }

    public function testGet()
    {
        $arr = array_flip(range('a','z'));
        $this->assertSame(0, Data::get($arr, 'a'));
        $this->assertSame(25, Data::get($arr, 'z'));
        $this->assertSame('not found', Data::get($arr, 'aa', 'not found'));
        $this->assertSame([
            'a' => 0,
            'h' => 7,
            'k' => 10,
        ], Data::get($arr, ['a','h','k']));
        $this->assertSame([
            'a' => 0,
            'b' => 1,
            1 => 'nope',
            'c' => 2,
            '239o' => 'nope',
        ], Data::get($arr, ['a', 'b', 1, 'c', '239o'], 'nope'));
    }

    public function testContains()
    {
        $range = range('a','z');
        $this->assertTrue(Data::contains($range, 'd'));
        $this->assertTrue(Data::contains($range, 'z', 25));
        $this->assertFalse(Data::contains($range, 'ds'));
        $this->assertFalse(Data::contains($range, 'd', 1));
    }

    public function testContainsAll()
    {
        $range = range('a','z');
        $this->assertTrue(Data::containsAll($range, ['a','v','d','c','w']));
        $this->assertFalse(Data::containsAll($range, ['a','v','d','c','w', 10]));
        $this->assertTrue(Data::containsAll($range, ['a','b','c','d','e'], true));
        $this->assertFalse(Data::containsAll($range, ['a','b','c','d','e',48 => 'f'], true));
    }

    public function testCut()
    {
        $range = range('a','z') + range(1, 100);
        $cut = Data::cut($range, 20);
        $this->assertSame(
            [array_slice($range, 0, 20, true), array_slice($range, 20, null, true)],
            $cut
        );
        $this->assertSame(
            [[], $range],
            Data::cut($range, 0)
        );
        $this->assertSame(
            [array_slice($range, 0, -25, true), array_slice($range, -25, null, true)],
            Data::cut($range, -25)
        );
        $this->assertSame(
            [array_slice($range, 0, 3, true), array_slice($range, 3, null, true)],
            Data::cut($range, 3)
        );
        $this->assertSame(
            [$range, []],
            Data::cut($range, 150)
        );
        $this->assertSame(
            [[], $range],
            Data::cut($range, -150)
        );
    }

    public function testUnion()
    {
        $items = range(0, 9);
        $more = range('a', 'z');
        $evenmore = range('A', 'K');
        $andevenmore = range('L', 'Z');
        $withkeys = ['foo' => 'bar', 'boo' => 'far', 'loud' => 'kid'];
        $final = ['a' => 'c', 4 => 'asdf', 'foo' => 'asdf', 'asdf' =>  'fa'];
        $expected = [];
        foreach ([$items, $more, $evenmore, $andevenmore, $withkeys, $final] as $piece) {
            foreach ($piece as $val) {
                $expected[] = $val;
            }
        }
        $this->assertSame($expected, Data::union($items, $more, $evenmore, $andevenmore, $withkeys, $final));
    }

    public function testMerge()
    {
        $items = range(0, 9);
        $more = range('a', 'z');
        $evenmore = range('A', 'K');
        $andevenmore = range('L', 'Z');
        $withkeys = ['foo' => 'bar', 'boo' => 'far', 'loud' => 'kid'];
        $final = ['a' => 'c', 4 => 'asdf', 'foo' => 'asdf', 'asdf' =>  'fa'];
        $expected = [];
        foreach ([$items, $more, $evenmore, $andevenmore, $withkeys, $final] as $piece) {
            foreach ($piece as $key => $val) {
                $expected[$key] = $val;
            }
        }
        $this->assertSame($expected, Data::merge($items, $more, $evenmore, $andevenmore, $withkeys, $final));
    }

    public function testSplice()
    {
        $letters = range('a', 'z');
        $oddfives = range(0, 200, 5);
        $expected = [
            0 => 0,
            1 => 5,
            2 => 10,
            3 => 15,
            4 => 20,
            5 => 25,
            6 => 30,
            7 => 35,
            8 => 40,
            9 => 45,
            10 => 50,
            11 => 55,
            12 => 60,
            13 => 65,
            14 => 70,
            15 => 'a',
            16 => 'b',
            17 => 'c',
            18 => 'd',
            19 => 'e',
            20 => 'f',
            21 => 'g',
            22 => 'h',
            23 => 'i',
            24 => 'j',
            25 => 'k',
            26 => 'l',
            27 => 'm',
            28 => 'n',
            29 => 'o',
            30 => 'p',
            31 => 'q',
            32 => 'r',
            33 => 's',
            34 => 't',
            35 => 'u',
            36 => 'v',
            37 => 'w',
            38 => 'x',
            39 => 'y',
            40 => 'z',
            41 => 135,
            42 => 140,
            43 => 145,
            44 => 150,
            45 => 155,
            46 => 160,
            47 => 165,
            48 => 170,
            49 => 175,
            50 => 180,
            51 => 185,
            52 => 190,
            53 => 195,
            54 => 200,
        ];
        $this->assertSame($expected, Data::splice($oddfives, $letters, 15, 12));
    }

    public function testCutInto()
    {
        $arr = range(1,50);
        $arr2 = range('a','i');
        $this->assertSame(
            Data::union(range(1,25), range('a','i'), range(26, 50)),
            Data::cutInto($arr, $arr2, 25)
        );
    }

    public function testIndexOf()
    {
        $arr = range('z',  'a');
        $arr2 = range(0, 100);
        $this->assertEquals($arr2[18], Data::indexOf($arr2, '18'));
        $this->assertEquals(11, Data::indexOf($arr, 'o'));
    }

    public function testIndexOfLast()
    {
        $arr = range('z',  'a');
        $arr = Data::union($arr, range('z', 'k'), range(1, 59), range(10, 73), range('a', 'x'));
        $arr2 = range(0, 100);
        $this->assertEquals(167, Data::indexOfLast($arr, 'c'));
        $this->assertEquals(107, Data::indexOfLast($arr, '16'));
        $this->assertNull(Data::indexOfLast($arr, 'notfound'));
        $this->assertEquals(39, Data::indexOfLast($arr2, 39, true));
        $this->assertNull(Data::indexOfLast($arr2, '39', true));
    }

    public function testExceptionThrownByIndexOf()
    {
        $value = 100;
        $arr = range(1, 20);
        $this->expectException(ValueNotFoundException::class);
        Data::indexOf($arr, $value, true, true);
    }
}
