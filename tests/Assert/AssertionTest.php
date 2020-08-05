<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils\Assert;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Mainframe\Utils\Assert\Assertion;
use MainframeTest\Utils\MainframeTestCase;

class AssertionTest extends MainframeTestCase
{
    public function testAssertPassesSmartCallbackArgs()
    {
        $assertions = new Assertion();
        $assertions->or(
            true,
            fn ($asst) => $asst->gt(45),
            fn ($asst) => $asst->endsWith('food'),
            fn ($asst) => $asst->beginsWith('chinese')
        );
        $assertions->matches('/123 [\w ]+/i');
        $this->assertTrue($assertions('123 some chinese food'));
    }

    public function testAssertRecordsErrors()
    {
        $assertions = new Assertion();
        $assertions->or(
            5 > 10,
            false,
            fn ($val) => true,
            //fn (Assertion $a) => $a->endsWith('food'),
            //fn (Assertion $a) => $a->beginsWith('chinese'),
            //fn (Assertion $a) => $a->callback(fn ($value) => $value === 'foooooo')
        );
        //dd($assertions);
        //$assertions->matches('/^123 [\w ]+$/i');
        $this->assertTrue($assertions('123'));
    }

    public function testAssertIfAllowsConditionalRules()
    {
        $assertions = new Assertion();
    }
}