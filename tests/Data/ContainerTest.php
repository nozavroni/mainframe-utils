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

use Mainframe\Utils\Data\Struct;
use Mainframe\Utils\Helper\Data;
use MainframeTest\Utils\MainframeTestCase;

class ContainerTest extends MainframeTestCase
{
    public function testCreateContainerInitStorage()
    {
        $a = Data::getKeyByPos($this->samples['users'], 3);
        $this->assertEquals('6ZD869R-3', $a);
        $data = new Struct($arr = [
            'user' => Data::getByPos($this->samples['users'], -4),
            'nums' => range(0, 100),
            'chars' => range(' ', '}'),
        ]);
        dd($arr);
        $this->assertEquals(
            '',
            Data::getByPos($data, 3)
        );
    }
}