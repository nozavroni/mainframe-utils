<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils\Strings\Regex;

use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Strings\Regex\Pattern;
use MainframeTest\Utils\MainframeTestCase;
use function Mainframe\Utils\re;

class PatternTest extends MainframeTestCase
{
    public function testRENSFunctionReturnsPattern()
    {
        $this->assertInstanceOf(Pattern::class, re('/asdf/'));
    }

    public function testPatternInstantiation()
    {
        $p = Pattern::create('/\d+/is');
        $this->assertSame('/\d+/is', (string) $p);
        $p = Pattern::create('~[abc]x?~');
        $this->assertSame('~[abc]x?~', (string) $p);
        $p = Pattern::create('<\d+>is');
        $this->assertSame('<\d+>is', (string) $p);
        $p = Pattern::create('{12345[6-9]+}imx');
        $this->assertSame('{12345[6-9]+}imx', (string) $p);
    }

    public function testPatternCreateMethod()
    {
        $p = new Pattern('\d', 'isxPOKJA');
        $this->assertSame('/\d/isxJA', (string) $p);
        $p = new Pattern('^\d', 'i');
        $this->assertSame('/^\d/i', (string) $p);
        $p = new Pattern('123[a-z]+$', 'sx');
        $this->assertSame('/123[a-z]+$/sx', (string) $p);
        $p = new Pattern('^\d$', 'i');
        $this->assertSame('/^\d$/i', (string) $p);
        $p = new Pattern('123[a-z]+$', 'sx', '<>');
        $this->assertSame('<123[a-z]+$>sx', (string) $p);
        $p = new Pattern('^\d$', 'i', '~');
        $this->assertSame('~^\d$~i', (string) $p);
    }

    public function testSetDefaultDelim()
    {
        $p = Pattern::create('/asdfasdf/');
        $this->assertSame('/asdfasdf/', (string)$p);
        Pattern::setDefaultDelim('~');
        $p2 = new Pattern('aaaaa');
        $this->assertSame('~aaaaa~', (string)$p2);
    }

    public function testSetDefaultDelimThrowsExceptionIfProvidedInvalidInput()
    {
        $this->expectException(InvalidArgumentException::class);
        Pattern::setDefaultDelim('asdf');
    }

    public function testInstantiateEnsuresValidDelim()
    {
        $p = Pattern::create('<asdf>uuuuuXsuXuXASDJFK zdxasd');
        $this->assertSame('<>', $p->getDelims());
        $this->assertSame('asdf', $p->getPattern());
        $this->assertSame('uXsASDJx', $p->getModifiers());
        $this->assertSame('<asdf>uXsASDJx', (string)$p);
    }

}