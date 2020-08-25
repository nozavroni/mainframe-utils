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

use Mainframe\Utils\Assert\Rule\InstanceOfRule;
use Mainframe\Utils\Assert\Rule\MinLengthRule;
use Mainframe\Utils\Assert\Rule\MaxLengthRule;
use Mainframe\Utils\Assert\Rule\RuleInterface;
use Mainframe\Utils\Assert\Value;
use MainframeTest\Utils\MainframeTestCase;
use stdClass;

class RuleTest extends MainframeTestCase
{
    public function testMinLengthRule()
    {
        $valuebad = new Value('test');
        $valuegood = new Value('testtesttest');
        $minlen = new MinLengthRule(10);
        $this->assertFalse($minlen->validate($valuebad));
        $this->assertTrue($minlen->validate($valuegood));
    }

    public function testMaxLengthRule()
    {
        $valuegood = new Value('test');
        $valuebad = new Value('testtesttest');
        $maxlen = new MaxLengthRule(10);
        $this->assertFalse($maxlen->validate($valuebad));
        $this->assertTrue($maxlen->validate($valuegood));
    }

    public function testInstanceOfRule()
    {
        $good = new Value(new InstanceOfRule(stdClass::class));
        $bad = new Value(new stdClass());
        $rule = new InstanceOfRule(RuleInterface::class);
        $this->assertTrue($rule->validate($good));
        $this->assertFalse($rule->validate($bad));
    }
}