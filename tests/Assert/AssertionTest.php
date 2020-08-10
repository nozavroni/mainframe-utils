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

use Mainframe\Utils\Assert\Assert;
use MainframeTest\Utils\MainframeTestCase;

class AssertionTest extends MainframeTestCase
{

    public function testAssertRuleChain()
    {
        $assert = new Assert();
        $assert->is(fn($a) => dd($a));
        //$assert->xor(true, true);
        dump($assert);
        $this->assertTrue($assert->isValid('test'));
    }

//    public function testValueAssertion()
//    {
//        $assert = new Assert();
//        $assert->and(
//            function (Assert $a) {
//                return $a->or(
//                    fn($a) => $a::NotEmpty(),
//                    false
//                );
//            },
//            fn (Assert $a) => $a::Regex('\w+')
//        );
//        $this->assertTrue($assert->isValid('I am the value'));
//    }


//    public function testAssertPassesSmartCallbackArgs()
//    {
//        $assertions = new Value();
//        $assertions->or(
//            true,
//            fn ($asst) => $asst->gt(45),
//            fn ($asst) => $asst->endsWith('food'),
//            fn ($asst) => $asst->beginsWith('chinese')
//        );
//        $assertions->matches('/123 [\w ]+/i');
//        $this->assertTrue($assertions('123 some chinese food'));
//    }
//
//    public function testAssertRecordsErrors()
//    {
//        $assertions = new Value();
//        $assertions->or(
//            // 5 > 10,
//            // false,
//            // fn ($val) => false,
//            fn (Value $a) => $a->endsWith('food'),
//            fn (Value $a) => $a->endsWith('233'),
//            fn (Value $a) => $a->endsWith('asdf'),
//            //fn (Value $a) => $a->beginsWith('chinese'),
//            //fn (Value $a) => $a->callback(fn ($value) => $value === 'foooooo')
//        );
//        //dd($assertions);
//        //$assertions->matches('/^123 [\w ]+$/i');
//        $this->assertTrue($assertions('123'));
//    }
//
//    public function testAssertIfAllowsConditionalRules()
//    {
//        $assertions = new Value();
//    }
}