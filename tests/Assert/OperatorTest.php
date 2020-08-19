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

use Mainframe\Utils\Assert\Operator\AndOperator;
use Mainframe\Utils\Assert\Value;
use MainframeTest\Utils\MainframeTestCase;

class OperatorTest extends MainframeTestCase
{
    public function testAndOperator()
    {
        $and = new AndOperator();
    }
}