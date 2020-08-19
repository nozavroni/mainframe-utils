<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Assert;

use Mainframe\Utils\Assert\A;
use Mainframe\Utils\Assert\RuleSet;
use Mainframe\Utils\Assert\Value;
use MainframeTest\Utils\MainframeTestCase;

ini_set('display_errors','On');
error_reporting(E_ALL);

class AssertTest extends MainframeTestCase
{
//    public function testValueWorksAsSortOfPromise()
//    {
//        $assert = new RuleSet();
//        $assert->xor( // internall add a rule that accepts a value and then runs a function that checks a xor b
//            fn (Value $v) => $v->length(50), // when isValid is called, the rule mentioned above calls this with the value
//            fn (Value $v) => $v->match('\w\d\w\d\d\d')
//        );
//        $assert->and( // add another rule to the rule stack
//            function (Value $v) {
//                $v->equals(1293);
//            },
//            function (Value $v) {
//                $this->unless( // each rule needs to have its own rule stack for this to get added to
//                    // so when the above "and" is checked, it internally needs to check its own sub-rules
//                    // so I guess the rule set needs to be created for each level
//                    fn (Value $v) => $v->matches('\d+'),
//                    fn (Value $v) => $v->minLength(5)
//                );
//            },
//            fn(Value $v) => $this->or(false, fn() => true, fn(Value $v) => $v->notNull())
//        );
//        $assert('this is the value'); // throw an exception
//        $assert->isValid('value'); // return bool
//    }
    public function testOperator()
    {
        $assert = new RuleSet();
        $assert->and(
            function(Value $v) {
                return A::and(fn(Value $v) => $v->maxLength(15), fn($v) => A::xor(true, false));
            },
            fn(Value $v) => A::and(fn(Value $v) => $v->minLength(1), true),
            fn(Value $v) => $v->maxLength(10)
        );
        $good = new Value('as');
        $bad = new Value('asdfasdfasdfasdf');
        $this->assertTrue($assert->isValid($good));
        $this->assertFalse($assert->isValid($bad));
    }
}