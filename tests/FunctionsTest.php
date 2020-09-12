<?php

use MainframeTest\Utils\MainframeTestCase;

/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

class FunctionsTest extends MainframeTestCase
{
    public function testAbsoluteOffsetLength()
    {
        $items = str_split('chickenlickenfrickinidjit');
        $offset = -6;
        $length = -1;

        $this->assertSame([15, 19], absolute_offset_length($items, -10, 4));
        $this->assertSame([0, 25], absolute_offset_length($items, -100, 300));
        $this->assertSame([21, 23], absolute_offset_length($items, -4, -2));
        $this->assertSame([0, 0], absolute_offset_length($items, -12, -14));
        $this->assertSame([10, 14], absolute_offset_length($items, 10, 4));
        $this->assertSame([23, 25], absolute_offset_length($items, 23, 40));
        $this->assertSame([2, 6], absolute_offset_length($items, -23, 4));
    }

}