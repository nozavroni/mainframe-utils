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

use Mainframe\Utils\Helper\Data;
use MainframeTest\Utils\MainframeTestCase;

class DataTest extends MainframeTestCase
{
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
}
