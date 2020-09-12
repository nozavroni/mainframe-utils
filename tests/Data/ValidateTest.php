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

use Mainframe\Utils\Data\Validate\V;
use Mainframe\Utils\Data\Validate\Rule\IsAlphaRule;
use Mainframe\Utils\Data\Validate\RuleSet;
use Mainframe\Utils\Data\Value;
use MainframeTest\Utils\MainframeTestCase;

ini_set('display_errors','On');
error_reporting(E_ALL);

class ValidateTest extends MainframeTestCase
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
                return V::and(fn(Value $v) => $v->maxLength(15), fn($v) => V::xor(true, false));
            },
            fn(Value $v) => V::and(fn(Value $v) => $v->minLength(1), true),
            fn(Value $v) => $v->maxLength(10),
            fn(Value $v) => V::xor($v->isFloat(), $v->isString())
        );
        $good = new Value('as');
        $bad = new Value('asdfasdfasdfasdf');
        $bad2 = new Value(100);
        $this->assertTrue($assert->isValid($good));
        $this->assertTrue($assert->isValid($good));
        $this->assertTrue($assert->isValid($good));
        $this->assertTrue($assert->isValid($good));
        $this->assertFalse($assert->isValid($bad));
        $this->assertFalse($assert->isValid($bad2));
    }

    public function testOrOperator()
    {
        $assert = new RuleSet();
        $assert->or(
            fn (Value $v) => $v->matches('[a-z]+\d+'),
            fn (Value $v) => $v->regex('/^abc/')
        );
        $this->assertTrue($assert->isValid('asdfasdfasdf1123'));
        $this->assertTrue($assert->isValid('abcasldkjf2'));
        $this->assertTrue($assert->isValid('abc123'));
        $this->assertTrue($assert->isValid('s5'));
        $this->assertTrue($assert->isValid('a3'));
        $this->assertFalse($assert->isValid('zzzzzzzz'));
        $this->assertFalse($assert->isValid('23'));
        $this->assertFalse($assert->isValid('one'));
        $this->assertFalse($assert->isValid('876678'));
    }

    public function testWhenOperator()
    {
        $assert = new RuleSet();
        $assert->when(
            fn (Value $v) => $v->isAlpha(),
            fn (Value $v) => $v->matches('asdf\w+'),
            fn (Value $v) => $v->matches('\d+')
        );
        $this->assertTrue($assert->isValid('asdflkjkljen'));
        $this->assertTrue($assert->isValid('4352345'));
        $this->assertFalse($assert->isValid('fffff'));
        $this->assertFalse($assert->isValid('fff33333'));
    }

    public function testUnlessOperator()
    {
        $assert = new RuleSet();
        $assert->unless(
            fn (Value $v) => $v->isNumeric(),
            fn (Value $v) => $v->matches('asdf\w+'),
            fn (Value $v) => $v->matches('\d+')
        );
        $this->assertTrue($assert->isValid('asdflkjkljen'));
        $this->assertTrue($assert->isValid('4352345'));
        $this->assertFalse($assert->isValid('fffff'));
        $this->assertFalse($assert->isValid('fff33333'));
    }


}